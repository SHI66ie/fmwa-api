# Admin Panel Troubleshooting Guide

## 1. TinyMCE Editor (CDN vs Local)

### Current Setup
Your admin pages use **TinyMCE from CDN** (`cdn.tiny.cloud`):
```html
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
```

### Why CDN is GOOD:
✅ **Faster loading** - Uses global CDN caching  
✅ **No server storage** - Saves your disk space  
✅ **Always updated** - Gets security patches automatically  
✅ **Standard practice** - 90% of websites do this  

### cPanel Does NOT Have:
❌ Built-in rich text editor API  
❌ Pre-installed TinyMCE or CKEditor  
❌ Server-side editor alternatives  

### When to Host Locally:
Only if you have:
- Security policy against external CDNs
- Need to work offline
- Want custom TinyMCE plugins

### How to Host TinyMCE Locally (Optional):
1. Download TinyMCE: https://www.tiny.cloud/get-tiny/self-hosted/
2. Extract to `admin/assets/tinymce/`
3. Replace CDN link with:
   ```html
   <script src="assets/tinymce/tinymce.min.js"></script>
   ```

**Recommendation**: Keep using CDN unless you have a specific reason not to.

---

## 2. File Upload Issues (Pictures/Media)

### Common Errors and Solutions:

#### Error: "File type not allowed"
**Cause**: MIME type validation  
**Check**: What file type are you uploading?  
**Solution**: The API now accepts:
- All images: `image/jpeg`, `image/png`, `image/gif`, `image/webp`, etc.
- All videos: `video/mp4`, `video/webm`, etc.
- PDFs: `application/pdf`

If still failing, check browser console for the actual MIME type being rejected.

#### Error: "Upload directory is not writable"
**Cause**: cPanel folder permissions  
**Fix in cPanel**:
1. Go to **File Manager**
2. Navigate to `/public_html/images/`
3. Right-click on `uploads` folder → **Change Permissions**
4. Set to **755** or **775**
5. Check "Recurse into subdirectories"

#### Error: "Failed to write file to disk"
**Cause**: PHP upload settings too restrictive  
**Fix in cPanel**:
1. Go to **MultiPHP INI Editor**
2. Select your domain
3. Change these settings:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_execution_time = 300
   memory_limit = 256M
   ```
4. Save changes

#### Error: "No file uploaded" 
**Cause**: FormData field name mismatch  
**Current Fix**: API now accepts both `file` and `media` field names  
**Check**: Your frontend is sending the right field name

---

## 3. Database/Modification Errors

### "Failed to load posts/categories"
**Fixed**: Frontend now uses correct `data.data` response format  
**Action**: Upload the latest files:
- `admin/posts.php`
- `admin/categories.php`
- `admin/api/media.php`

### Session Errors
If you see "Warning: session_start()...":
1. Check `/sessions/` folder exists in root
2. Folder should have `.htaccess` with:
   ```apache
   <Files "*">
       Order allow,deny
       Deny from all
   </Files>
   ```
3. Sessions folder permissions: **700** or **755**

### Database Connection Issues
Check `config.php` has correct credentials:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'womenaffairsgov_fmwa_db');
define('DB_USER', 'womenaffairsgov_admin');
define('DB_PASS', '@vCoLTL27N.gEfF');
```

---

## 4. Getting Detailed Error Messages

### For Upload Errors:
1. Try uploading a file
2. Open **Browser DevTools** (F12)
3. Go to **Console** tab
4. Look for red errors
5. Go to **Network** tab → click failed request → **Response**
6. You'll now see detailed diagnostics:
   ```json
   {
     "success": false,
     "message": "Upload directory is not writable",
     "debug": {
       "upload_dir": "../../images/uploads/",
       "dir_exists": true,
       "dir_writable": false
     }
   }
   ```

### For Server-Side Errors:
Check **cPanel Error Log**:
1. Go to **cPanel** → **Metrics** → **Errors**
2. Look for recent PHP errors
3. Errors are also in `/home/womenaffairsgov/logs/error_log`

---

## 5. Quick Diagnostic Checklist

Run through this checklist when things aren't working:

### Permissions:
- [ ] `/images/uploads/` exists and is **writable (755 or 775)**
- [ ] `/sessions/` exists with **700 or 755**
- [ ] `/sessions/.htaccess` denies direct access

### PHP Settings (MultiPHP INI Editor):
- [ ] `upload_max_filesize` ≥ 10M
- [ ] `post_max_size` ≥ 10M
- [ ] `max_execution_time` ≥ 300
- [ ] `display_errors` = Off (for production)

### Database:
- [ ] Credentials in `config.php` are correct
- [ ] Tables exist: `posts`, `categories`, `media`, `users`, `activity_log`
- [ ] Admin user exists with correct password

### Files Uploaded:
- [ ] Latest `admin/posts.php` (API response fix)
- [ ] Latest `admin/categories.php` (API response fix)
- [ ] Latest `admin/api/media.php` (detailed error logging)
- [ ] Latest `config.php` (session handling)

---

## 6. Test Each Component

### Test Posts:
1. Go to `admin/posts.php`
2. You should see the welcome post
3. Click "Edit" - should load in TinyMCE
4. Make a change and save
5. If error: Check Console → Network → posts.php response

### Test Categories:
1. Go to `admin/categories.php`
2. Should see default categories
3. Click "Add Category"
4. Fill form and save
5. If error: Check Console → Network → categories.php response

### Test Media Upload:
1. Go to `admin/media.php`
2. Click "Upload Media" or drag-drop a **JPG image**
3. If error: Check Console for exact error message
4. Note the error and check against solutions above

### Test Page Editor:
1. Go to `admin/pages.php`
2. Select a page (e.g., "Home Page")
3. Make a small edit
4. Click "Save Changes"
5. Verify changes on public site

---

## 7. Contact Support (If Stuck)

When asking for help, provide:
1. **Exact error message** from browser console
2. **Screenshot** of Network tab → Response
3. **What action** triggered the error
4. **What you tried** from this guide
5. **Server error log** excerpt if available

---

## Quick Commands for Checking Logs

In cPanel Terminal or SSH:

```bash
# Check recent PHP errors
tail -100 ~/logs/error_log

# Check upload folder permissions
ls -la ~/public_html/images/

# Check if sessions folder exists
ls -la ~/public_html/sessions/

# Check PHP upload settings
php -i | grep upload_max_filesize
```

---

**Last Updated**: After API response format fixes and enhanced error logging
