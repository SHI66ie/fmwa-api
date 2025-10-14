# 🚀 Getting Started with FMWA Admin Panel

## Welcome!

You now have a complete admin panel to manage your website content. This guide will get you up and running in **5 minutes**.

## 📋 What You Can Do

### ✍️ Create & Edit Content
- Write news articles and blog posts
- Add images and videos
- Edit any page on your website
- Organize content with categories

### 🖼️ Manage Media
- Upload images, videos, and documents
- Drag and drop files
- Edit image details
- Copy URLs for use in posts

### 📄 Edit Pages
- Modify HTML pages directly
- Syntax highlighting for code
- Preview changes before publishing
- Automatic backups

## ⚡ Quick Setup

### Step 1: Import Database (2 minutes)

Open your MySQL client (phpMyAdmin, MySQL Workbench, or command line):

```bash
mysql -u root -p
```

Then run:

```sql
CREATE DATABASE IF NOT EXISTS fmwa_db;
USE fmwa_db;
SOURCE database/schema.sql;
```

Or in phpMyAdmin:
1. Create database `fmwa_db`
2. Import file: `database/schema.sql`

### Step 2: Configure Database (1 minute)

Edit `config.php` and update these lines:

```php
define('DB_HOST', 'localhost');      // Usually 'localhost'
define('DB_NAME', 'fmwa_db');        // Keep as is
define('DB_USER', 'your_username');  // Your MySQL username
define('DB_PASS', 'your_password');  // Your MySQL password
```

### Step 3: Set Permissions (1 minute)

**On Linux/Mac:**
```bash
chmod 755 images/uploads
chmod 755 data
```

**On Windows:**
1. Right-click `images/uploads` folder
2. Properties → Security → Edit
3. Add "Write" permission for your web server user

### Step 4: Access Admin Panel (1 minute)

Open your browser and go to:

```
http://localhost/admin/login.php
```

Or if deployed:
```
http://your-domain.com/admin/login.php
```

**Login with:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT:** Change this password after first login!

## 🎯 Your First Tasks

### 1. Create Your First Post

1. Click **Posts & News** in the sidebar
2. Click **New Post** button
3. Enter a title and content
4. Click **Publish**

### 2. Upload an Image

1. Click **Media Library** in the sidebar
2. Drag and drop an image
3. Click on the image to edit details
4. Copy the URL to use in posts

### 3. Edit a Page

1. Click **Pages** in the sidebar
2. Select a page from the list
3. Make your changes
4. Press **Ctrl+S** or click **Save**

## 📚 Admin Pages Overview

| Page | What It Does |
|------|--------------|
| **Dashboard** | Overview of your site with statistics |
| **Posts & News** | Create and manage articles |
| **Media Library** | Upload and organize files |
| **Pages** | Edit HTML pages directly |
| **Categories** | Organize your content |
| **Settings** | View system info and help |

## 🎨 Features at a Glance

### Rich Text Editor
- Bold, italic, underline
- Headings and lists
- Links and images
- Tables
- HTML code view

### Media Management
- Drag & drop upload
- Multiple file upload
- Image preview
- Edit metadata
- Search files
- Copy URLs

### Page Editor
- Syntax highlighting
- Line numbers
- Auto-indent
- Find & replace
- Keyboard shortcuts

## 🔐 Security Tips

1. **Change Default Password**
   - Go to database
   - Update admin password
   - Use a strong password

2. **Use HTTPS**
   - Enable SSL certificate
   - Force HTTPS in production

3. **Backup Regularly**
   - Database: `mysqldump -u root -p fmwa_db > backup.sql`
   - Files: Copy `images/uploads/` folder

4. **Keep Updated**
   - Update PHP regularly
   - Update MySQL/MariaDB
   - Monitor security advisories

## 🆘 Need Help?

### Common Questions

**Q: I forgot my password**  
A: Reset it in the database or contact your system administrator

**Q: Upload fails**  
A: Check folder permissions and PHP upload limits

**Q: Can't save pages**  
A: Verify file permissions on HTML files

**Q: Database connection error**  
A: Check credentials in `config.php` and ensure MySQL is running

### Get Support

- 📧 Email: admin@fmwa.gov.ng
- 📖 Read: [ADMIN_SETUP_GUIDE.md](ADMIN_SETUP_GUIDE.md)
- 📋 Check: [ADMIN_SUMMARY.md](ADMIN_SUMMARY.md)

## 📖 Documentation Files

- **GETTING_STARTED.md** (this file) - Quick start guide
- **ADMIN_README.md** - Quick reference
- **ADMIN_SETUP_GUIDE.md** - Detailed setup instructions
- **ADMIN_SUMMARY.md** - Technical overview

## ✅ Checklist

Before going live, make sure you:

- [ ] Imported database schema
- [ ] Configured database connection
- [ ] Set folder permissions
- [ ] Changed default password
- [ ] Tested login
- [ ] Created a test post
- [ ] Uploaded a test image
- [ ] Edited a test page
- [ ] Set up backups
- [ ] Enabled HTTPS

## 🎉 You're Ready!

Your admin panel is now set up and ready to use. Start creating content and managing your website!

### Quick Links

- [Login Page](/admin/login.php)
- [Dashboard](/admin/dashboard.php)
- [View Website](/index.html)

---

**Need more help?** Check out the other documentation files or contact support.

**Happy content managing!** 🚀
