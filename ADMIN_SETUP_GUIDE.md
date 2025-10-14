# FMWA Admin Panel Setup Guide

## Overview

This admin panel provides a complete content management system (CMS) for the Federal Ministry of Women Affairs website. You can manage posts, media, pages, and categories through an intuitive web interface.

## Features

- ✅ **User Authentication** - Secure login system with role-based access
- ✅ **Media Library** - Upload and manage images, videos, and documents
- ✅ **Posts & News Management** - Create, edit, and publish content with rich text editor
- ✅ **Page Editor** - Edit HTML pages directly with syntax highlighting
- ✅ **Categories** - Organize content with categories
- ✅ **Modern UI** - Beautiful, responsive interface

## Installation Steps

### 1. Database Setup

First, you need to set up the MySQL database:

```bash
# Access MySQL
mysql -u root -p

# Create the database and import schema
mysql -u root -p < database/schema.sql
```

Or manually:

1. Open phpMyAdmin or MySQL Workbench
2. Create a new database named `fmwa_db`
3. Import the file `database/schema.sql`

### 2. Configure Database Connection

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'fmwa_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### 3. Set Up File Permissions

Ensure the following directories are writable:

```bash
chmod 755 images/uploads
chmod 755 data
```

On Windows, right-click the folders → Properties → Security → Edit → Add write permissions.

### 4. Access the Admin Panel

Navigate to: `http://your-domain.com/admin/login.php`

**Default Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT:** Change the default password immediately after first login!

## Usage Guide

### Managing Posts & News

1. Go to **Posts & News** from the sidebar
2. Click **New Post** button
3. Fill in:
   - Title (required)
   - Content (required)
   - Excerpt (optional summary)
   - Categories
   - Featured Image
4. Click **Save as Draft** or **Publish**

### Uploading Media

1. Go to **Media Library**
2. Drag & drop files or click **Choose Files**
3. Supported formats: JPG, PNG, GIF, WebP, SVG, MP4, WebM, PDF
4. Click on any media to edit title, alt text, and caption

### Editing Pages

1. Go to **Pages**
2. Select a page from the list
3. Edit the HTML code in the editor
4. Press **Ctrl+S** or click **Save Changes**
5. Click **Preview** to view changes

### Managing Categories

1. Go to **Categories**
2. Click **New Category**
3. Enter name and description
4. Categories can be assigned to posts

## Security Best Practices

### Change Default Password

1. Access the database
2. Run this SQL to change admin password:

```sql
UPDATE users 
SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD' 
WHERE username = 'admin';
```

Or use PHP to generate a new hash:

```php
<?php
echo password_hash('your_new_password', PASSWORD_DEFAULT);
?>
```

### Protect Admin Directory

Add this to your `.htaccess` file in the admin directory:

```apache
# Restrict access by IP (optional)
<Limit GET POST>
    order deny,allow
    deny from all
    allow from YOUR_IP_ADDRESS
</Limit>
```

### Enable HTTPS

Always use HTTPS in production. Update `config.php`:

```php
define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);
```

## Troubleshooting

### Cannot Login

1. Check database connection in `config.php`
2. Verify the users table exists
3. Check PHP error logs

### Media Upload Fails

1. Check folder permissions: `chmod 755 images/uploads`
2. Verify PHP upload limits in `php.ini`:
   ```ini
   upload_max_filesize = 20M
   post_max_size = 20M
   ```

### Pages Not Saving

1. Check file permissions on HTML files
2. Verify the API endpoint is accessible
3. Check browser console for errors

### Database Connection Error

1. Verify MySQL service is running
2. Check credentials in `config.php`
3. Ensure database `fmwa_db` exists

## API Endpoints

The admin panel uses these API endpoints:

- `POST /admin/api/media.php` - Upload media
- `GET /admin/api/media.php` - List media
- `PUT /admin/api/media.php` - Update media
- `DELETE /admin/api/media.php` - Delete media

- `POST /admin/api/posts.php` - Create post
- `GET /admin/api/posts.php` - List posts
- `PUT /admin/api/posts.php` - Update post
- `DELETE /admin/api/posts.php` - Delete post

- `POST /admin/api/categories.php` - Create category
- `GET /admin/api/categories.php` - List categories
- `PUT /admin/api/categories.php` - Update category
- `DELETE /admin/api/categories.php` - Delete category

- `GET /api/page?path=...` - Get page content
- `POST /api/page` - Save page content

## File Structure

```
fmwa-api/
├── admin/
│   ├── auth.php              # Authentication class
│   ├── login.php             # Login page
│   ├── dashboard.php         # Main dashboard
│   ├── posts.php             # Posts management
│   ├── media.php             # Media library
│   ├── pages.php             # Page editor
│   ├── categories.php        # Categories management
│   ├── settings.php          # Settings page
│   ├── logout.php            # Logout handler
│   └── api/
│       ├── media.php         # Media API
│       ├── posts.php         # Posts API
│       └── categories.php    # Categories API
├── database/
│   └── schema.sql            # Database schema
├── images/
│   └── uploads/              # Uploaded media files
├── data/                     # JSON data files
├── config.php                # Configuration file
└── ADMIN_SETUP_GUIDE.md      # This file
```

## Backup & Maintenance

### Database Backup

```bash
mysqldump -u root -p fmwa_db > backup_$(date +%Y%m%d).sql
```

### File Backup

Regularly backup these directories:
- `images/uploads/`
- `data/`
- All HTML files

### Updates

To update the admin panel:
1. Backup your database and files
2. Replace admin files with new versions
3. Run any database migrations if provided
4. Clear browser cache

## Support

For issues or questions:
- Email: admin@fmwa.gov.ng
- Check error logs in: `error_log` or PHP error logs

## License

© 2025 Federal Ministry of Women Affairs. All Rights Reserved.
