-- Add template_3 as an option for the CMS
-- Run this SQL to enable the new Tech/Dark theme

USE english_news_cms;

-- Update the active_template setting description to include template_3
UPDATE site_settings 
SET description = 'Active frontend template (template_1, template_2, or template_3)'
WHERE setting_key = 'active_template';

-- To switch to the new Tech/Dark theme (Template 3), run:
-- UPDATE site_settings SET setting_value = 'template_3' WHERE setting_key = 'active_template';

-- Verify current template
SELECT setting_key, setting_value, description 
FROM site_settings 
WHERE setting_key = 'active_template';
