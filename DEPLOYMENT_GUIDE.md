# 🚀 Production Deployment Guide

## 📦 **Files to Upload to Production Server**

### **1. Root Directory Files:**
```
✅ index.php                 ← New PHP homepage (REQUIRED)
✅ .htaccess                 ← Updated routing rules (REQUIRED)
```

### **2. Components Directory:**
```
✅ components/
├── header.php              ← Department page header
├── footer.php              ← Department page footer
├── navigation.php          ← Main site navigation
├── head.php               ← Common head section
└── main-footer.php        ← Main site footer
```

### **3. Department Pages:**
```
✅ departments/
├── child-development.php
├── community-development-social-intervention.php
├── economic-services.php
├── finance-accounting.php
├── gender-affairs.php
├── general-services.php
├── human-resource-management.php
├── nutrition.php
├── planning-research-statistics.php
├── procurement.php
├── reform-coordination-service-improvement.php
├── special-duties.php
├── women-development.php
└── .htaccess              ← Department URL rules
```

### **4. Existing Files (Keep These):**
```
✅ Keep all existing files:
├── css/ (all stylesheets)
├── js/ (all JavaScript files)
├── images/ (all images)
├── about.html, mandate.html, etc.
└── All other existing files
```

## 🔧 **Deployment Steps:**

### **Step 1: Backup Current Site**
- Download/backup your current website files
- Especially backup: index.html, .htaccess

### **Step 2: Upload New Files**
Upload these files to your web server:

**Root Directory:**
- `index.php` → `/public_html/index.php`
- `.htaccess` → `/public_html/.htaccess` (replace existing)

**Components Directory:**
- Create `/public_html/components/` folder
- Upload all 5 PHP files from `components/` folder

**Departments Directory:**
- Upload all 13 `.php` files to `/public_html/departments/`
- Upload `departments/.htaccess` to `/public_html/departments/.htaccess`

### **Step 3: Set File Permissions**
Set proper permissions (if using cPanel/FTP):
- PHP files: 644 or 755
- Directories: 755
- .htaccess files: 644

### **Step 4: Test the Deployment**
After upload, test these URLs:
- `https://womenaffairs.gov.ng/` (should serve index.php)
- `https://womenaffairs.gov.ng/index.php` (direct access)
- `https://womenaffairs.gov.ng/departments/women-development.php`
- `https://womenaffairs.gov.ng/departments/finance-accounting.php`

## ✅ **What Will Work After Deployment:**

### **Homepage Access:**
- `https://womenaffairs.gov.ng/` → serves index.php
- `https://womenaffairs.gov.ng/index.php` → direct access
- `https://womenaffairs.gov.ng/index.html` → redirects to index.php

### **Department Pages:**
- All 13 departments accessible via PHP
- Consistent header/footer across all pages
- Working navigation between pages

### **Features:**
- Professional PHP homepage with all sections
- Director photo placeholders on all department pages
- Responsive design on all devices
- SEO-optimized URLs and structure

## 🚨 **Important Notes:**

### **Server Requirements:**
- PHP 7.4+ (you have PHP 8.3.26 ✅)
- Apache with mod_rewrite enabled
- .htaccess support enabled

### **Backup Strategy:**
- Keep `index.html` as backup
- Test on staging environment first (if available)
- Monitor for any errors after deployment

### **Rollback Plan:**
If issues occur:
1. Rename `index.php` to `index.php.backup`
2. Restore original `.htaccess`
3. Site will fall back to `index.html`

## 📋 **Deployment Checklist:**

- [ ] Backup current website
- [ ] Upload `index.php` to root
- [ ] Upload updated `.htaccess` to root
- [ ] Create `components/` directory
- [ ] Upload all 5 component PHP files
- [ ] Upload all 13 department PHP files
- [ ] Upload `departments/.htaccess`
- [ ] Set proper file permissions
- [ ] Test homepage: `https://womenaffairs.gov.ng/`
- [ ] Test department page: `https://womenaffairs.gov.ng/departments/women-development.php`
- [ ] Verify navigation works between pages
- [ ] Check mobile responsiveness

## 🎉 **After Successful Deployment:**

Your website will have:
- ✅ Professional PHP homepage
- ✅ 13 consistent department pages
- ✅ Unified navigation system
- ✅ Director photo placeholders
- ✅ Mobile-responsive design
- ✅ SEO-optimized structure

**Ready for deployment!** 🚀
