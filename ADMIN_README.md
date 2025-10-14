# FMWA Admin Panel - Quick Start

## 🚀 Quick Access

**Admin URL:** `http://your-domain.com/admin/login.php`

**Default Login:**
- Username: `admin`
- Password: `admin123`

⚠️ **Change this password immediately after first login!**

## 📋 Prerequisites

Before using the admin panel, ensure you have:

1. ✅ PHP 7.4 or higher
2. ✅ MySQL 5.7 or higher
3. ✅ Apache/Nginx web server
4. ✅ PDO PHP extension enabled
5. ✅ GD or ImageMagick for image processing

## 🔧 Quick Setup (5 Minutes)

### Step 1: Import Database

```bash
mysql -u root -p < database/schema.sql
```

Or use phpMyAdmin to import `database/schema.sql`

### Step 2: Configure Database

Edit `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'fmwa_db');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Step 3: Set Permissions

```bash
chmod 755 images/uploads
chmod 755 data
```

### Step 4: Login

Go to `/admin/login.php` and use default credentials.

## 📚 What Can You Do?

### ✍️ Content Management
- Create and publish news articles
- Write blog posts with rich text editor
- Add featured images to posts
- Organize content with categories

### 🖼️ Media Management
- Upload images, videos, and documents
- Drag & drop file uploads
- Edit media metadata (title, alt text, caption)
- Copy media URLs for use in content

### 📄 Page Editing
- Edit any HTML page on your site
- Syntax highlighting for code
- Live preview of changes
- Direct HTML editing with CodeMirror

### 📁 Organization
- Create and manage categories
- Tag posts with multiple categories
- Track post counts per category

## 🎨 Features

- **Modern UI** - Beautiful gradient design with responsive layout
- **Rich Text Editor** - TinyMCE for WYSIWYG content editing
- **Code Editor** - CodeMirror for HTML page editing
- **Drag & Drop** - Easy file uploads
- **Real-time Updates** - Instant feedback on all actions
- **Secure** - Password hashing, session management, activity logging

## 📱 Admin Pages

| Page | URL | Purpose |
|------|-----|---------|
| Dashboard | `/admin/dashboard.php` | Overview and statistics |
| Posts & News | `/admin/posts.php` | Create and manage posts |
| Media Library | `/admin/media.php` | Upload and manage files |
| Pages | `/admin/pages.php` | Edit HTML pages |
| Categories | `/admin/categories.php` | Manage categories |
| Settings | `/admin/settings.php` | View system info |

## 🔐 Security Features

- ✅ Password hashing with bcrypt
- ✅ Session-based authentication
- ✅ Activity logging
- ✅ SQL injection protection (PDO prepared statements)
- ✅ XSS protection
- ✅ CSRF protection ready

## 🐛 Common Issues

### "Cannot connect to database"
- Check MySQL is running
- Verify credentials in `config.php`
- Ensure database `fmwa_db` exists

### "Permission denied" on file upload
- Run: `chmod 755 images/uploads`
- Check PHP user has write permissions

### "Page not found" errors
- Ensure `.htaccess` is working
- Check Apache mod_rewrite is enabled

### Cannot login
- Verify database has users table
- Check default user exists
- Clear browser cookies/cache

## 📖 Full Documentation

For detailed setup and usage instructions, see:
- [ADMIN_SETUP_GUIDE.md](ADMIN_SETUP_GUIDE.md) - Complete setup guide
- [database/schema.sql](database/schema.sql) - Database structure

## 🆘 Support

Need help?
- Email: admin@fmwa.gov.ng
- Check PHP error logs
- Review browser console for JavaScript errors

## 🔄 Updates

To update the admin panel:
1. Backup database: `mysqldump -u root -p fmwa_db > backup.sql`
2. Backup files in `images/uploads/`
3. Replace admin files
4. Test thoroughly

## 📝 Notes

- Always backup before making changes
- Test in development before production
- Keep PHP and MySQL updated
- Use HTTPS in production
- Change default passwords
- Regularly backup your data

---

**Version:** 1.0.0  
**Last Updated:** October 2025  
**Developed for:** Federal Ministry of Women Affairs
