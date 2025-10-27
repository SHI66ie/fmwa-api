# 🗄️ FMWA Database Setup Guide

Complete guide for setting up the MySQL database for the Federal Ministry of Women Affairs website.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Quick Setup](#quick-setup)
- [Manual Setup](#manual-setup)
- [Database Structure](#database-structure)
- [Configuration](#configuration)
- [Verification](#verification)
- [Troubleshooting](#troubleshooting)

## 🔧 Prerequisites

Before setting up the database, ensure you have:

- **MySQL 5.7+** or **MariaDB 10.2+** installed
- **PHP 7.4+** with PDO MySQL extension
- Database credentials (username and password)
- Command line access or phpMyAdmin

## ⚡ Quick Setup

### Option 1: Automated Setup (Recommended)

**On Windows:**
```bash
cd database
setup.bat
```

**On Linux/Mac:**
```bash
cd database
chmod +x setup.sh
./setup.sh
```

Follow the prompts to enter your MySQL credentials.

### Option 2: Using PHP Setup Script

1. Navigate to `http://localhost/setup.php` in your browser
2. Enter your database credentials
3. Click "Continue" and then "Install Database"

## 🔨 Manual Setup

### Step 1: Create Database

Open MySQL command line or phpMyAdmin and run:

```sql
CREATE DATABASE IF NOT EXISTS fmwa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 2: Import Schema

**Using MySQL Command Line:**

```bash
mysql -u root -p fmwa_db < database/schema.sql
```

**Using phpMyAdmin:**

1. Select the `fmwa_db` database
2. Click on "Import" tab
3. Choose `database/schema.sql` file
4. Click "Go"

### Step 3: Configure Database Connection

Edit `config.php` in the root directory:

```php
define('DB_HOST', 'localhost');      // Your MySQL host
define('DB_NAME', 'fmwa_db');        // Database name
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
```

## 📊 Database Structure

The database includes the following tables:

### Core Tables

| Table | Description |
|-------|-------------|
| **users** | User accounts and authentication |
| **posts** | Blog posts, news, and articles |
| **categories** | Content categories |
| **post_categories** | Many-to-many relationship between posts and categories |
| **post_meta** | Additional post metadata |
| **comments** | User comments on posts |
| **media** | Uploaded files and images |
| **pages** | Static pages |
| **settings** | Site configuration settings |
| **visitor_stats** | Website visitor analytics |
| **activity_log** | User activity tracking |

### Default Data

The schema automatically creates:

- **Admin User**
  - Username: `admin`
  - Password: `admin123`
  - Email: `admin@fmwa.gov.ng`
  - ⚠️ **Change this password immediately after first login!**

- **Default Categories**
  - News
  - Events
  - Announcements
  - Press Releases
  - Programs
  - Reports

- **Sample Content**
  - Welcome post with basic information

## ⚙️ Configuration

### Database Connection

The `Database` class uses a singleton pattern for efficient connection management:

```php
require_once 'includes/Database.php';
$db = Database::getInstance();
```

### Using Model Classes

```php
// Posts
require_once 'includes/Post.php';
$post = new Post();
$posts = $post->getPublished(10);

// Users
require_once 'includes/User.php';
$user = new User();
$user = $user->getByUsername('admin');

// Categories
require_once 'includes/Category.php';
$category = new Category();
$categories = $category->getAll();
```

## ✅ Verification

### Test Database Connection

Run the test script:

```bash
php test_db.php
```

Or visit in browser:
```
http://localhost/test_db.php
```

Expected output:
```
Connected to MySQL version: X.X.X
Inserted user with ID: X
Fetched user: testuser_XXXXX
Updated 1 user(s)
Transaction committed successfully
All tests completed successfully!
```

### Verify Tables

```sql
USE fmwa_db;
SHOW TABLES;
```

You should see 11 tables listed.

### Check Default Data

```sql
SELECT * FROM users;
SELECT * FROM categories;
SELECT * FROM posts;
```

## 🔐 Security Recommendations

1. **Change Default Password**
   ```sql
   UPDATE users 
   SET password = '$2y$10$YOUR_NEW_HASHED_PASSWORD' 
   WHERE username = 'admin';
   ```
   Or use the admin panel to change it.

2. **Create Database User with Limited Privileges**
   ```sql
   CREATE USER 'fmwa_user'@'localhost' IDENTIFIED BY 'strong_password';
   GRANT SELECT, INSERT, UPDATE, DELETE ON fmwa_db.* TO 'fmwa_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Update config.php with new credentials**
   ```php
   define('DB_USER', 'fmwa_user');
   define('DB_PASS', 'strong_password');
   ```

4. **Secure config.php**
   - Move outside web root if possible
   - Set file permissions to 600 (Linux/Mac)
   - Add to .gitignore

## 🐛 Troubleshooting

### Connection Failed

**Error:** `Database connection failed: SQLSTATE[HY000] [1045] Access denied`

**Solution:**
- Verify MySQL credentials in `config.php`
- Check if MySQL service is running
- Ensure user has proper permissions

### Table Already Exists

**Error:** `Table 'users' already exists`

**Solution:**
- Drop existing database: `DROP DATABASE fmwa_db;`
- Re-run setup script
- Or manually drop tables before importing

### Character Encoding Issues

**Error:** Garbled text or special characters not displaying

**Solution:**
- Ensure database uses `utf8mb4` charset
- Check PHP file encoding is UTF-8
- Verify MySQL connection charset in `Database.php`

### Import Fails

**Error:** `ERROR 1064: You have an error in your SQL syntax`

**Solution:**
- Check MySQL version compatibility
- Ensure schema.sql file is not corrupted
- Try importing in smaller chunks

### Permission Denied

**Error:** `Access denied for user 'root'@'localhost'`

**Solution:**
- Reset MySQL root password
- Check user privileges: `SHOW GRANTS FOR 'root'@'localhost';`
- Grant necessary permissions

## 📚 Additional Resources

### Backup Database

```bash
mysqldump -u root -p fmwa_db > backup_$(date +%Y%m%d).sql
```

### Restore Database

```bash
mysql -u root -p fmwa_db < backup_20250114.sql
```

### View Database Size

```sql
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'fmwa_db'
GROUP BY table_schema;
```

### Optimize Tables

```sql
OPTIMIZE TABLE users, posts, categories, media;
```

## 🆘 Need Help?

If you encounter issues:

1. Check the error logs in `php_error.log`
2. Enable debug mode in `config.php`
3. Review MySQL error log
4. Consult the [GETTING_STARTED.md](GETTING_STARTED.md) guide
5. Contact system administrator

## 📝 Database Schema Details

### Users Table Structure

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor', 'author', 'subscriber'),
    status ENUM('active', 'inactive', 'suspended'),
    -- ... additional fields
);
```

### Posts Table Structure

```sql
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    author_id INT NOT NULL,
    status ENUM('draft', 'published', 'scheduled', 'archived'),
    post_type ENUM('post', 'news', 'event', 'page'),
    -- ... additional fields
);
```

For complete schema details, see `database/schema.sql`.

## ✨ Next Steps

After successful database setup:

1. ✅ Test database connection
2. ✅ Change default admin password
3. ✅ Configure site settings
4. ✅ Create your first post
5. ✅ Upload media files
6. ✅ Set up backups

---

**Database Version:** 1.0.0  
**Last Updated:** January 14, 2025  
**Compatibility:** MySQL 5.7+, MariaDB 10.2+, PHP 7.4+
