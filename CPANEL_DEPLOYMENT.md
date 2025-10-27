# 🚀 cPanel Deployment Checklist

## ✅ Files Ready to Upload

All files are ready for deployment to cPanel!

## 📤 Step-by-Step Deployment

### Step 1: Upload Database Schema

1. **Login to cPanel**
2. **Open phpMyAdmin**
3. **Create Database** (if needed):
   - Go to "MySQL Databases"
   - Create database: `your_cpanel_username_fmwa`
   - Note the full database name
4. **Import Schema**:
   - Select the database in phpMyAdmin
   - Click "Import" tab
   - Upload: `database/schema.sql`
   - Click "Go"
   - Wait for success message

### Step 2: Update Database Credentials

Edit `config.php` with your cPanel database details:

```php
// Database configuration
define('DB_HOST', 'localhost');  // Usually 'localhost' on cPanel
define('DB_NAME', 'your_cpanel_username_fmwa');  // Your actual database name
define('DB_USER', 'your_cpanel_username_dbuser');  // Your database user
define('DB_PASS', 'your_database_password');  // Your database password
```

### Step 3: Upload Files to cPanel

**Upload these directories:**
- `includes/` - All PHP classes
- `database/` - Schema and documentation (optional, for reference)

**Upload these files:**
- `config.php` - Database configuration (with updated credentials)
- `test_connection.php` - For testing (remove after verification)
- All your existing HTML/PHP files

### Step 4: Set File Permissions

In cPanel File Manager:
- `includes/` folder: **755**
- `config.php`: **644**
- PHP files: **644**
- Directories: **755**

### Step 5: Test Database Connection

Visit: `https://yourdomain.com/test_connection.php`

You should see:
- ✓ Connected to database successfully
- ✓ 11 tables found
- ✓ Admin user exists
- ✓ All database operations working

### Step 6: Security Steps

1. **Change Admin Password**:
   - Login to admin panel
   - Change from `admin123` to strong password

2. **Remove Test Files** (after verification):
   - Delete `test_connection.php`
   - Delete `test_db.php`
   - Delete `test_db_connection.php`

3. **Disable Error Display** in `config.php`:
   ```php
   // Error reporting (production)
   error_reporting(0);
   ini_set('display_errors', '0');
   ```

4. **Secure config.php**:
   - Ensure it's not in public_html root if possible
   - Or add to `.htaccess`:
   ```apache
   <Files "config.php">
       Order Allow,Deny
       Deny from all
   </Files>
   ```

## 📋 What Gets Created in Database

After importing `schema.sql`:

### Tables (11)
- users
- posts
- categories
- post_categories
- post_meta
- comments
- media
- pages
- settings
- visitor_stats
- activity_log

### Default Data
- **Admin User**: username `admin`, password `admin123`
- **6 Categories**: News, Events, Announcements, Press Releases, Programs, Reports
- **10 Settings**: Site configuration
- **1 Welcome Post**: Sample content

### Database Views (2)
- vw_published_posts
- vw_visitor_summary

## 🔧 cPanel-Specific Notes

### Database Name Format
cPanel typically prefixes database names:
- Format: `cpanel_username_dbname`
- Example: `fmwauser_fmwa_db`

### Database User Format
- Format: `cpanel_username_dbuser`
- Example: `fmwauser_admin`

### Finding Your Credentials
1. cPanel → MySQL Databases
2. Note the full database name
3. Note the database user
4. Use the password you set

### Common cPanel Paths
- **public_html/** - Web root
- **public_html/includes/** - PHP classes
- **Outside public_html/** - Config files (more secure)

## ⚠️ Important Warnings

1. **Backup First**: If you have existing data, backup before importing
2. **Schema Drops Tables**: The schema file drops existing tables with same names
3. **Change Password**: Default admin password is `admin123` - change immediately
4. **Remove Test Files**: Delete test files after verification
5. **Secure Config**: Protect config.php from web access

## 🐛 Troubleshooting

### "Access Denied" Error
- Check database credentials in config.php
- Verify database user has permissions
- Ensure database name is correct (with prefix)

### "Database Not Found"
- Create database in cPanel first
- Use full database name (with prefix)
- Check spelling

### "Table Already Exists"
- Drop existing tables first
- Or use different database name
- Check if previous import succeeded

### "Import Failed"
- Check file size limits in phpMyAdmin
- Try importing in parts if needed
- Check MySQL version compatibility

### Connection Works Locally But Not on cPanel
- Update DB_HOST (might need IP instead of localhost)
- Check database user permissions
- Verify firewall/security settings

## ✅ Final Checklist

Before going live:

- [ ] Database imported successfully
- [ ] Config.php updated with cPanel credentials
- [ ] Test connection successful
- [ ] All 11 tables exist
- [ ] Default admin user exists
- [ ] Admin password changed
- [ ] Test files removed
- [ ] Error display disabled
- [ ] File permissions set correctly
- [ ] Config.php secured
- [ ] Backup created
- [ ] Admin panel accessible

## 🎉 You're Ready!

Once all steps are complete, your database is live and ready to use!

**Admin Login**: `https://yourdomain.com/admin/login.php`
- Username: `admin`
- Password: (your new password)

---

**Need help?** Check [DATABASE_SETUP.md](DATABASE_SETUP.md) for detailed troubleshooting.
