#!/bin/bash
# Database Setup Script for FMWA
# This script will create the database and import the schema

echo "========================================"
echo "FMWA Database Setup"
echo "========================================"
echo ""

# Check if MySQL is accessible
if ! command -v mysql &> /dev/null; then
    echo "ERROR: MySQL is not found in PATH"
    echo "Please install MySQL or add it to your PATH"
    exit 1
fi

echo "MySQL found!"
echo ""

# Prompt for MySQL credentials
read -p "Enter MySQL username (default: root): " MYSQL_USER
MYSQL_USER=${MYSQL_USER:-root}

read -sp "Enter MySQL password (press Enter if none): " MYSQL_PASS
echo ""
echo ""

echo "Connecting to MySQL..."
echo ""

# Create database and import schema
if [ -z "$MYSQL_PASS" ]; then
    mysql -u "$MYSQL_USER" < schema.sql
else
    mysql -u "$MYSQL_USER" -p"$MYSQL_PASS" < schema.sql
fi

if [ $? -eq 0 ]; then
    echo ""
    echo "========================================"
    echo "Database setup completed successfully!"
    echo "========================================"
    echo ""
    echo "Database: fmwa_db"
    echo "Default admin username: admin"
    echo "Default admin password: admin123"
    echo ""
    echo "IMPORTANT: Change the admin password after first login!"
    echo ""
else
    echo ""
    echo "========================================"
    echo "ERROR: Database setup failed!"
    echo "========================================"
    echo ""
    echo "Please check your MySQL credentials and try again."
    echo ""
    exit 1
fi
