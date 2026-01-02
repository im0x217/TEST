# English News CMS - Project Documentation

## 📁 Project Structure

```
EnglishNewsApp/
├── admin/                          # Admin Panel (Backend)
│   ├── includes/                   # Admin includes (header, footer)
│   ├── index.php                   # Admin Dashboard
│   ├── login.php                   # Admin Login
│   ├── logout.php                  # Logout handler
│   ├── news-manage.php             # News listing/management
│   ├── news-add.php                # Add new news
│   ├── news-edit.php               # Edit existing news
│   ├── news-delete.php             # Delete news
│   ├── settings.php                # Theme switcher & social media
│   └── statistics.php              # Statistics manager
│
├── config/
│   └── database.php                # Database connection (PDO) + helpers
│
├── templates/                      # Frontend Templates
│   ├── template_1/                 # Layout A
│   │   ├── index.php               # Homepage for template 1
│   │   └── news-detail.php         # News detail for template 1
│   └── template_2/                 # Layout B
│       ├── index.php               # Homepage for template 2
│       └── news-detail.php         # News detail for template 2
│
├── assets/
│   ├── images/                     # Static images (logos, icons)
│   └── uploads/                    # User-uploaded images
│
├── includes/
│   └── functions.php               # Common helper functions
│
├── index.php                       # Main frontend entry point
├── news-detail.php                 # News detail entry point
└── database.sql                    # Database schema
```

---

## 🗄️ Database Schema Overview

### Tables Created:

1. **`admin_users`**
   - Stores admin credentials with password hashing
   - Default login: `admin` / `admin123` (⚠️ Change immediately!)

2. **`news`**
   - Stores all news articles
   - Fields: `id`, `title`, `slug`, `content`, `image_path`, `status`, `views`, `created_at`, `updated_at`
   - Includes full-text search index for title/content
   - SEO-friendly slugs for URLs

3. **`site_settings`**
   - Key-value store for global configuration
   - **Critical settings:**
     - `active_template` → Controls which template is active ('template_1' or 'template_2')
     - `social_x`, `social_discord`, `social_telegram` → Social media URLs
     - Other settings: site name, tagline, news per page, etc.

4. **`statistics`**
   - Dynamic statistics for homepage
   - Fields: `id`, `label_name`, `number_value`, `icon`, `display_order`, `is_visible`
   - Admin can toggle visibility and edit values

5. **`page_views`** (Optional)
   - Tracks analytics and page views

---

## 🎨 Template System Architecture

### How the Multi-Template System Works:

```
┌──────────────┐
│  index.php   │  ← Main entry point
└──────┬───────┘
       │
       ├─→ Loads config/database.php
       ├─→ Loads includes/functions.php
       ├─→ Calls getActiveTemplate() → Reads 'active_template' from DB
       │
       ├──→ If 'template_1' → includes templates/template_1/index.php
       └──→ If 'template_2' → includes templates/template_2/index.php
```

### Template Selection Logic:

**File: `index.php` (Main Frontend Entry Point)**
```php
<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

// Get active template from database
$activeTemplate = getActiveTemplate(); // Returns 'template_1' or 'template_2'

// Fetch data (news, settings, statistics)
$db = getDB();
$news = $db->query("SELECT * FROM news WHERE status='published' ORDER BY created_at DESC")->fetchAll();
$settings = getAllSettings();
$statistics = $db->query("SELECT * FROM statistics WHERE is_visible=1 ORDER BY display_order")->fetchAll();

// Pass data to template and render
renderTemplate($activeTemplate, 'index.php', [
    'news' => $news,
    'settings' => $settings,
    'statistics' => $statistics
]);
?>
```

### Template Differences:

**Template 1 (template_1):**
- Statistics box in **top-right corner**
- Color scheme: Blue/Purple gradient
- Grid layout: 3-column news grid
- Modern card design with shadows

**Template 2 (template_2):**
- Statistics box in **sidebar (left side)**
- Color scheme: Orange/Red gradient
- Grid layout: 2-column news grid with larger images
- Magazine-style layout

---

## 🔐 Security Features (2026 Standards)

1. **Password Hashing**: PHP `password_hash()` with bcrypt
2. **Prepared Statements**: All queries use PDO prepared statements (SQL injection prevention)
3. **CSRF Protection**: Token-based form validation
4. **XSS Prevention**: All output sanitized with `htmlspecialchars()`
5. **File Upload Validation**: MIME type checking, size limits, extension validation
6. **Session Security**: Secure session handling with regeneration
7. **Error Handling**: Production-ready error pages, no sensitive data exposure

---

## 🚀 Setup Instructions

### 1. Database Setup:
```sql
-- Import the database.sql file into MySQL
mysql -u root -p < database.sql

-- Or via phpMyAdmin:
-- 1. Open phpMyAdmin
-- 2. Create database 'english_news_cms'
-- 3. Import database.sql file
```

### 2. Configuration:
Edit **`config/database.php`** if needed:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'english_news_cms');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. File Permissions:
Ensure the uploads directory is writable:
```bash
chmod 755 assets/uploads/
```

### 4. Access Points:
- **Frontend**: `http://localhost/EnglishNewsApp/`
- **Admin Panel**: `http://localhost/EnglishNewsApp/admin/`
- **Default Login**: `admin` / `admin123`

---

## 🛠️ Admin Panel Features

### Dashboard (`admin/index.php`)
- Overview statistics
- Quick actions
- Recent news

### News Management (`admin/news-manage.php`)
- **CRUD Operations**: Create, Read, Update, Delete
- **SEO**: Auto-generate slugs from titles
- **Image Upload**: Support for JPG, PNG, GIF, WebP (max 5MB)
- **Status**: Draft or Published
- **View Counter**: Track article views

### Theme Switcher (`admin/settings.php`)
```html
<select name="active_template">
    <option value="template_1">Layout A (Blue Theme)</option>
    <option value="template_2">Layout B (Orange Theme)</option>
</select>
```
- Instantly switches frontend layout
- No cache, changes reflect immediately

### Statistics Manager (`admin/statistics.php`)
- Add/Edit/Delete statistics
- Toggle visibility (show/hide on frontend)
- Reorder with drag-and-drop (future enhancement)
- Custom labels and values (e.g., "50K+ Readers")

### Social Media Manager (`admin/settings.php`)
- Update X (Twitter) URL
- Update Discord invite URL
- Update Telegram channel URL
- URLs displayed as icons on homepage

---

## 📊 Database Helper Functions

Located in **`config/database.php`**:

```php
// Get single setting
$template = getSetting('active_template');

// Update setting
updateSetting('active_template', 'template_2');

// Get all settings
$settings = getAllSettings();

// Sanitize output
echo sanitize($userInput);

// Generate slug
$slug = generateSlug('Breaking News Today!'); // 'breaking-news-today'

// Format date
echo formatDate($newsDate); // 'January 2, 2026'

// Truncate text
echo truncateText($content, 150); // 'Lorem ipsum dolor...'
```

---

## 🎯 Frontend Features

### Homepage (index.php)
- Display published news articles
- Show visible statistics
- Social media icons (middle of page)
- Responsive design with TailwindCSS
- Template-based layout

### News Detail (news-detail.php)
- SEO-friendly URLs: `news-detail.php?slug=breaking-news-today`
- Full article content with images
- View counter (increments on each visit)
- Social sharing buttons
- Related news (future enhancement)

### Template Rendering
Both templates receive the same data structure:
```php
[
    'news' => [...],           // Array of news articles
    'settings' => [...],       // Site settings
    'statistics' => [...]      // Visible statistics
]
```

---

## 🔄 Template Switching Workflow

```
Admin Panel (admin/settings.php)
    ↓
User selects "Layout B" and clicks Save
    ↓
Updates site_settings table: active_template = 'template_2'
    ↓
Frontend (index.php)
    ↓
Calls getActiveTemplate() → Returns 'template_2'
    ↓
Includes templates/template_2/index.php
    ↓
Frontend now displays Layout B
```

**No page refresh needed** - Change is immediate on next page load.

---

## 📝 Next Steps (After Setup)

After reviewing this structure, we'll implement:

1. ✅ **Admin Panel Pages**:
   - Login system with session management
   - Dashboard with statistics
   - News CRUD operations
   - Settings manager
   - Statistics manager

2. ✅ **Frontend Templates**:
   - Template 1 (Blue theme with top-right stats)
   - Template 2 (Orange theme with sidebar stats)
   - Responsive layouts with TailwindCSS
   - SEO-optimized markup

3. ✅ **Features**:
   - Image upload handling
   - View counter
   - Social media integration
   - Search functionality (future)
   - Pagination (future)

---

## 💡 Key Design Decisions

1. **PDO over mysqli**: Modern, consistent API with better error handling
2. **Singleton Pattern**: Ensures single database connection
3. **Template Separation**: Clean separation between layouts
4. **Helper Functions**: Reusable utilities for common tasks
5. **Security First**: Input validation, output sanitization, CSRF protection
6. **2026 Standards**: Type hints, null safety, prepared statements, modern PHP

---

## 🎨 TailwindCSS Integration

Both templates use TailwindCSS via CDN:
```html
<script src="https://cdn.tailwindcss.com"></script>
```

**Custom Configuration** (in template files):
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#3b82f6',
                secondary: '#8b5cf6'
            }
        }
    }
}
```

---

## 🔍 SEO Features

- **Friendly URLs**: `/news-detail.php?slug=breaking-news-today`
- **Auto-generated slugs**: From article titles
- **Meta tags**: Dynamic title, description, OG tags
- **Semantic HTML**: Proper heading hierarchy
- **Fast loading**: Minimal dependencies, optimized images

---

## 📱 Responsive Design

Both templates are fully responsive:
- **Mobile**: Single column layout
- **Tablet**: 2-column grid
- **Desktop**: 3-column grid (template 1) or 2-column with sidebar (template 2)

---

## 🔒 Production Checklist

Before deploying:

- [ ] Change default admin password
- [ ] Update database credentials in `config/database.php`
- [ ] Create dedicated database user with limited privileges
- [ ] Enable error logging (disable display_errors)
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Configure HTTPS/SSL
- [ ] Set up database backups
- [ ] Configure .htaccess for security headers
- [ ] Add rate limiting for login attempts
- [ ] Implement captcha on login form

---

**Ready to proceed with code implementation!** 🚀

Would you like me to start implementing the admin panel files or the frontend templates first?
