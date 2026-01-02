FROM php:8.2-apache

# Install PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# Set working dir to project base (keeps /EnglishNewsApp/ paths intact)
WORKDIR /var/www/html/EnglishNewsApp

# Copy app
COPY . /var/www/html/EnglishNewsApp

# Adjust Apache docroot and directory permissions
RUN sed -i 's@DocumentRoot /var/www/html@DocumentRoot /var/www/html/EnglishNewsApp@' /etc/apache2/sites-available/000-default.conf \
 && printf "\n<Directory /var/www/html/EnglishNewsApp>\n AllowOverride All\n Require all granted\n</Directory>\n" >> /etc/apache2/sites-available/000-default.conf

# Increase upload limits (tune as needed)
RUN printf "upload_max_filesize=50M\npost_max_size=50M\n" > /usr/local/etc/php/conf.d/uploads.ini

# Expose default port
EXPOSE 80

CMD ["apache2-foreground"]
