# 🚀 PHP Visitor Counter - Deployment Guide

## ✅ What Changed

Your production server uses **PHP**, not Node.js. I've created a PHP version of the visitor counter API.

---

## 📁 New Files Created

### API Files (PHP Backend)
1. **`api/visitor-count.php`** - Returns current visitor count
2. **`api/track-visit.php`** - Increments visitor count
3. **`api/.htaccess`** - Apache configuration for API access

### Updated Files
1. **`js/visitor-counter.js`** - Now calls `.php` endpoints instead of Node.js

---

## 🚀 Deployment Steps for cPanel/PHP

### Step 1: Upload Files to Production

Upload these files to your cPanel File Manager:

```
/public_html/
├── api/
│   ├── visitor-count.php
│   ├── track-visit.php
│   └── .htaccess
├── js/
│   └── visitor-counter.js (updated)
├── css/
│   └── visitor-counter.css
├── data/
│   └── (will be auto-created)
└── index.html (already updated)
```

### Step 2: Set Permissions

In cPanel File Manager:

1. **Create `data` folder** (if it doesn't exist):
   - Right-click in `public_html`
   - New Folder → name it `data`

2. **Set folder permissions**:
   - Right-click `data` folder
   - Permissions → Set to `775` (rwxrwxr-x)
   - ✅ Check "Recurse into subdirectories"

3. **Set API file permissions**:
   - Right-click `api` folder
   - Permissions → Set to `755` for folder
   - Set to `644` for `.php` files

### Step 3: Test the API

Visit these URLs in your browser:

1. **Test Get Count:**
   ```
   http://www.womenaffairs.gov.ng/api/visitor-count.php
   ```
   Should return:
   ```json
   {
     "success": true,
     "totalVisits": 0,
     "uniqueVisitors": 0,
     "lastReset": "2025-10-06T11:38:02+01:00",
     "lastVisit": null
   }
   ```

2. **Test Track Visit:**
   ```bash
   curl -X POST http://www.womenaffairs.gov.ng/api/track-visit.php
   ```
   Should return:
   ```json
   {
     "success": true,
     "totalVisits": 1,
     "message": "Visit tracked successfully"
   }
   ```

### Step 4: Verify on Website

1. Visit: http://www.womenaffairs.gov.ng
2. Open browser console (F12)
3. Should see NO errors
4. Visitor counter appears in bottom-right corner
5. Shows current count

---

## 📋 Quick Upload Checklist

- [ ] Upload `api/visitor-count.php`
- [ ] Upload `api/track-visit.php`
- [ ] Upload `api/.htaccess`
- [ ] Upload `js/visitor-counter.js` (updated version)
- [ ] Create `data/` folder
- [ ] Set `data/` permissions to 775
- [ ] Set `api/` folder permissions to 755
- [ ] Set `.php` files permissions to 644
- [ ] Test API endpoints
- [ ] Visit website and check counter

---

## 🔧 File Permissions Reference

```
/public_html/
├── api/                    (755 - rwxr-xr-x)
│   ├── visitor-count.php   (644 - rw-r--r--)
│   ├── track-visit.php     (644 - rw-r--r--)
│   └── .htaccess           (644 - rw-r--r--)
├── data/                   (775 - rwxrwxr-x) ← IMPORTANT!
│   └── visitors.json       (664 - rw-rw-r--) ← Auto-created
├── js/
│   └── visitor-counter.js  (644 - rw-r--r--)
└── css/
    └── visitor-counter.css (644 - rw-r--r--)
```

---

## 🧪 Testing Commands

### Test from Command Line (if you have SSH):

```bash
# Test GET endpoint
curl http://www.womenaffairs.gov.ng/api/visitor-count.php

# Test POST endpoint
curl -X POST http://www.womenaffairs.gov.ng/api/track-visit.php

# Check if data file was created
ls -la data/visitors.json

# View current count
cat data/visitors.json
```

### Test from Browser:

1. **Open Developer Tools** (F12)
2. **Go to Console tab**
3. **Run this:**
   ```javascript
   fetch('http://www.womenaffairs.gov.ng/api/visitor-count.php')
     .then(r => r.json())
     .then(d => console.log(d));
   ```

---

## 🐛 Troubleshooting

### Issue: "File not found" error

**Solution:**
- Verify files are uploaded to correct location
- Check file names are exactly: `visitor-count.php` and `track-visit.php`
- Ensure they're in the `api/` folder

### Issue: "Permission denied" error

**Solution:**
```bash
# Set correct permissions
chmod 775 data/
chmod 644 api/*.php
```

### Issue: Counter shows 0 always

**Solution:**
- Check if `data/visitors.json` exists
- Verify `data/` folder is writable (775)
- Check PHP error logs in cPanel

### Issue: CORS errors in console

**Solution:**
- Ensure `api/.htaccess` is uploaded
- Check if `mod_headers` is enabled in cPanel
- Contact hosting provider if needed

---

## 📊 How It Works (PHP Version)

### Architecture:

```
Browser (JavaScript)
    ↓
    ├─→ POST /api/track-visit.php
    │   └─→ Increments count in data/visitors.json
    │
    └─→ GET /api/visitor-count.php
        └─→ Returns current count from data/visitors.json
```

### Data Flow:

1. **User visits page**
2. **JavaScript checks** sessionStorage
3. **If not tracked:** POST to `track-visit.php`
4. **PHP increments** count in `visitors.json`
5. **JavaScript fetches** current count from `visitor-count.php`
6. **Displays** in bottom-right corner
7. **Auto-refreshes** every 30 seconds

---

## 🎯 Advantages of PHP Version

✅ **No Node.js required** - Works on standard cPanel hosting  
✅ **Simple deployment** - Just upload PHP files  
✅ **No server restart** - Changes take effect immediately  
✅ **File-based storage** - No database needed  
✅ **Concurrent access** - File locking prevents race conditions  

---

## 📝 Manual Upload via cPanel File Manager

### Step-by-Step:

1. **Login to cPanel**
2. **Open File Manager**
3. **Navigate to `public_html`**
4. **Create `api` folder** (if doesn't exist)
5. **Upload files:**
   - Click "Upload" button
   - Select `visitor-count.php`
   - Select `track-visit.php`
   - Select `.htaccess`
   - Upload to `api/` folder
6. **Create `data` folder**
7. **Set permissions** (see above)
8. **Test!**

---

## ✅ Verification Checklist

After deployment, verify:

- [ ] `api/visitor-count.php` returns JSON
- [ ] `api/track-visit.php` increments count
- [ ] `data/visitors.json` file is created
- [ ] Website loads without errors
- [ ] Counter appears in bottom-right
- [ ] Counter shows a number
- [ ] Count increments on new visit
- [ ] Count doesn't change on refresh
- [ ] Mobile responsive

---

## 🔐 Security Notes

- ✅ File locking prevents concurrent write issues
- ✅ Input validation on all endpoints
- ✅ JSON encoding prevents injection
- ✅ Proper error handling
- ✅ No database = no SQL injection risk

---

## 📞 Need Help?

**Common Issues:**
1. **500 Error** → Check PHP error logs in cPanel
2. **404 Error** → Verify file paths and names
3. **Permission Error** → Set `data/` to 775
4. **CORS Error** → Upload `api/.htaccess`

**Where to Check:**
- cPanel → Error Logs
- cPanel → PHP Error Log
- Browser → Developer Console (F12)

---

**Ready to Deploy!** 🚀

Just upload the files and test the API endpoints!
