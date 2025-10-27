# Database Directory

This directory contains all database-related files for the FMWA website.

## 📁 Files

### `schema.sql`
Complete database schema with all tables, indexes, and default data.

**Contains:**
- 11 database tables
- Foreign key relationships
- Indexes for performance
- Default admin user
- Sample categories
- Initial settings
- Database views

**Usage:**
```bash
mysql -u root -p fmwa_db < schema.sql
```

### `setup.bat` (Windows)
Automated setup script for Windows systems.

**Usage:**
```cmd
setup.bat
```

### `setup.sh` (Linux/Mac)
Automated setup script for Unix-based systems.

**Usage:**
```bash
chmod +x setup.sh
./setup.sh
```

### `queries.sql`
Collection of common SQL queries for reference.

**Includes:**
- User management queries
- Post queries
- Category queries
- Statistics queries
- Maintenance queries
- Reporting queries

## 🚀 Quick Start

### Method 1: Automated Setup (Recommended)

**Windows:**
```cmd
cd database
setup.bat
```

**Linux/Mac:**
```bash
cd database
chmod +x setup.sh
./setup.sh
```

### Method 2: Manual Setup

1. **Create Database:**
   ```sql
   CREATE DATABASE fmwa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import Schema:**
   ```bash
   mysql -u root -p fmwa_db < schema.sql
   ```

3. **Configure Connection:**
   Edit `../config.php` with your database credentials.

### Method 3: Using PHP Setup

1. Navigate to `http://localhost/setup.php`
2. Follow the on-screen instructions

## 🔍 Verify Installation

Run the test script:
```bash
php ../test_connection.php
```

Or visit in browser:
```
http://localhost/test_connection.php
```

## 📊 Database Structure

### Tables Overview

| Table | Purpose | Records |
|-------|---------|---------|
| users | User accounts | 1 (admin) |
| posts | Content posts | 1 (welcome) |
| categories | Content categories | 6 |
| post_categories | Post-category links | - |
| post_meta | Post metadata | - |
| comments | User comments | - |
| media | Uploaded files | - |
| pages | Static pages | - |
| settings | Site settings | 10 |
| visitor_stats | Analytics | - |
| activity_log | Activity tracking | - |

### Default Credentials

**Admin User:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@fmwa.gov.ng`

⚠️ **Change this password immediately after first login!**

## 🔧 Maintenance

### Backup Database
```bash
mysqldump -u root -p fmwa_db > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
mysql -u root -p fmwa_db < backup_20250114.sql
```

### Optimize Tables
```sql
OPTIMIZE TABLE users, posts, categories, media;
```

### Check Database Size
```sql
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'fmwa_db';
```

## 📚 Documentation

For detailed setup instructions, see:
- [DATABASE_SETUP.md](../DATABASE_SETUP.md) - Complete setup guide
- [GETTING_STARTED.md](../GETTING_STARTED.md) - Quick start guide

## 🐛 Troubleshooting

### Connection Issues
- Verify MySQL is running
- Check credentials in `config.php`
- Ensure database exists
- Check user permissions

### Import Errors
- Check MySQL version compatibility
- Verify file encoding (UTF-8)
- Ensure sufficient privileges
- Check for syntax errors

### Performance Issues
- Add indexes to frequently queried columns
- Optimize tables regularly
- Monitor query performance
- Consider caching strategies

## 🔐 Security

1. **Change default password**
2. **Create limited privilege user**
3. **Secure config.php**
4. **Enable SSL for connections**
5. **Regular backups**
6. **Monitor activity logs**

## 📝 Version

**Schema Version:** 1.0.0  
**Last Updated:** January 14, 2025  
**Compatibility:** MySQL 5.7+, MariaDB 10.2+
