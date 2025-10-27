# 🚀 Quick Start: Database Setup

Get your FMWA database up and running in **3 minutes**!

## ⚡ Super Quick Setup

### Option 1: Automated (Easiest)

**On Windows:**
```cmd
cd database
setup.bat
```

**On Linux/Mac:**
```bash
cd database
chmod +x setup.sh
./setup.sh
```

Enter your MySQL credentials when prompted. Done! ✅

### Option 2: One Command

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS fmwa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p fmwa_db < database/schema.sql
```

### Option 3: Web Interface

1. Open browser: `http://localhost/setup.php`
2. Enter database credentials
3. Click "Install Database"

## ✅ Verify It Works

Visit: `http://localhost/test_connection.php`

You should see green checkmarks ✓ everywhere!

## 🔑 Default Login

- **Username:** `admin`
- **Password:** `admin123`
- **Email:** `admin@fmwa.gov.ng`

⚠️ **Change this password immediately!**

## 📦 What You Get

✅ **11 Database Tables**
- Users, Posts, Categories
- Media, Pages, Settings
- Comments, Visitor Stats
- Activity Logs

✅ **Default Data**
- Admin user account
- 6 content categories
- 10 site settings
- 1 welcome post

✅ **PHP Classes**
- `Database` - Connection manager
- `Post` - Post operations
- `User` - User management
- `Category` - Category handling

## 🎯 Next Steps

1. **Test Connection**
   ```bash
   php test_connection.php
   ```

2. **Update Config** (if needed)
   Edit `config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'fmwa_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Access Admin Panel**
   ```
   http://localhost/admin/login.php
   ```

4. **Change Password**
   - Login with default credentials
   - Go to profile settings
   - Update password

## 🔧 Quick Commands

### Backup Database
```bash
mysqldump -u root -p fmwa_db > backup.sql
```

### Restore Database
```bash
mysql -u root -p fmwa_db < backup.sql
```

### Check Tables
```sql
USE fmwa_db;
SHOW TABLES;
```

### View Data
```sql
SELECT * FROM users;
SELECT * FROM categories;
SELECT * FROM posts;
```

## 🐛 Troubleshooting

### Can't Connect?
1. Check MySQL is running
2. Verify credentials in `config.php`
3. Ensure database exists

### Import Failed?
1. Check MySQL version (need 5.7+)
2. Verify file path is correct
3. Check user permissions

### Still Issues?
Run the diagnostic:
```bash
php test_connection.php
```

## 📚 Full Documentation

For detailed information:
- [DATABASE_SETUP.md](DATABASE_SETUP.md) - Complete guide
- [database/README.md](database/README.md) - Database details
- [database/queries.sql](database/queries.sql) - Common queries

## 💡 Pro Tips

1. **Use Automated Setup** - Fastest and safest
2. **Test Connection First** - Before doing anything else
3. **Backup Regularly** - Before making changes
4. **Change Default Password** - Security first!
5. **Read the Docs** - When you need more details

## 🎉 You're Done!

Your database is ready. Start building! 🚀

---

**Need help?** Check [DATABASE_SETUP.md](DATABASE_SETUP.md) for detailed instructions.
