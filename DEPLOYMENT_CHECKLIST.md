# 🚀 Visitor Counter - Production Deployment Checklist

## Current Status
✅ Code committed to GitHub (main branch)  
✅ Visitor counter implemented locally  
⏳ Needs deployment to www.womenaffairs.gov.ng

---

## 📋 Pre-Deployment Checklist

### 1. ✅ Files Already Committed to GitHub
- [x] `server.js` - Updated with visitor counter API endpoints
- [x] `js/visitor-counter.js` - Frontend component
- [x] `css/visitor-counter.css` - Styling
- [x] `index.html` - Updated with visitor counter
- [x] `departments/economic-services.html` - Example integration
- [x] Documentation files

### 2. 🔍 What Needs to Happen on Production Server

#### A. Auto-Deployment via cPanel (If Configured)
Your `.cpanel.yml` file should automatically:
1. Detect the new GitHub commit
2. Run `npm install --production`
3. Restart the Node.js application

#### B. Manual Steps Required on Server

**Step 1: Verify Node.js App is Running**
- Login to cPanel
- Go to **Application Manager** or **Node.js Selector**
- Ensure your Node.js app is running on www.womenaffairs.gov.ng

**Step 2: Create Data Directory (If Not Exists)**
```bash
mkdir -p /home/YOUR_USERNAME/public_html/data
chmod 775 /home/YOUR_USERNAME/public_html/data
```

**Step 3: Verify File Permissions**
```bash
# Make sure these files are readable
chmod 644 js/visitor-counter.js
chmod 644 css/visitor-counter.css
chmod 644 index.html

# Make sure data directory is writable
chmod 775 data/
```

**Step 4: Restart Node.js Application**
- In cPanel → Application Manager
- Click "Restart" on your Node.js app
- OR via SSH:
```bash
touch tmp/restart.txt
```

---

## 🌐 Deployment Options

### Option 1: Automatic Deployment (Recommended)

Since you've already pushed to GitHub, cPanel should auto-deploy:

1. **Check cPanel Deployment Status:**
   - Login to cPanel
   - Go to **Git Version Control** or **Deployment**
   - Check if the latest commit (f37174d) is deployed
   - Look for any deployment errors

2. **If Auto-Deploy Worked:**
   - Visit: https://www.womenaffairs.gov.ng
   - Check browser console for errors
   - Visitor counter should appear in bottom-right corner

3. **If Auto-Deploy Failed:**
   - Check deployment logs in cPanel
   - Manually pull latest changes (see Option 2)

---

### Option 2: Manual Deployment via cPanel File Manager

1. **Login to cPanel**
2. **Go to File Manager**
3. **Navigate to your website root** (usually `public_html`)
4. **Upload/Replace these files:**
   - `server.js`
   - `js/visitor-counter.js`
   - `css/visitor-counter.css`
   - `index.html`
   - `departments/economic-services.html`

5. **Create `data` directory** if it doesn't exist
6. **Set permissions:**
   - `data/` folder: 775 (rwxrwxr-x)
   - All other files: 644 (rw-r--r--)

7. **Restart Node.js app** in Application Manager

---

### Option 3: Manual Deployment via SSH/FTP

#### Via SSH:
```bash
# Connect to your server
ssh your_username@womenaffairs.gov.ng

# Navigate to website directory
cd ~/public_html

# Pull latest changes from GitHub
git pull origin main

# Install dependencies (if needed)
npm install --production

# Create data directory
mkdir -p data
chmod 775 data

# Restart application
touch tmp/restart.txt
```

#### Via FTP:
1. Connect using FileZilla or similar
2. Upload the modified files to the correct directories
3. Set proper permissions
4. Restart Node.js app via cPanel

---

## 🧪 Testing After Deployment

### 1. Test API Endpoints
```bash
# Test visitor count endpoint
curl https://www.womenaffairs.gov.ng/api/visitor-count

# Should return JSON like:
# {"success":true,"totalVisits":0,"uniqueVisitors":0,...}
```

### 2. Test Website
1. Visit: https://www.womenaffairs.gov.ng
2. Open browser Developer Tools (F12)
3. Check Console for errors
4. Look for visitor counter in bottom-right corner
5. Verify it shows a number

### 3. Test Tracking
1. Open site in incognito/private window
2. Counter should increment by 1
3. Refresh page - counter should NOT increase
4. Close and reopen incognito - counter should increase again

---

## 🐛 Troubleshooting

### Issue: Counter Not Appearing

**Check:**
1. Browser console for JavaScript errors
2. Network tab - are CSS/JS files loading?
3. Is `visitor-counter.css` and `visitor-counter.js` accessible?

**Fix:**
```bash
# Verify files exist on server
ls -la js/visitor-counter.js
ls -la css/visitor-counter.css

# Check file permissions
chmod 644 js/visitor-counter.js
chmod 644 css/visitor-counter.css
```

---

### Issue: API Returns 404

**Check:**
1. Is Node.js app running?
2. Is `server.js` updated with visitor counter endpoints?
3. Check application logs in cPanel

**Fix:**
```bash
# Restart Node.js application
touch tmp/restart.txt

# OR via cPanel Application Manager
# Click "Restart" button
```

---

### Issue: "Permission Denied" Writing to data/visitors.json

**Check:**
```bash
ls -la data/
```

**Fix:**
```bash
# Set correct permissions
chmod 775 data/
chown your_username:your_username data/

# If file exists but not writable
chmod 664 data/visitors.json
```

---

### Issue: Counter Shows 0 Always

**Check:**
1. Is `data/visitors.json` being created?
2. Does the server have write permissions?
3. Check server logs for errors

**Fix:**
```bash
# Manually create the file
echo '{"totalVisits":0,"uniqueVisitors":0,"lastReset":"'$(date -Iseconds)'"}' > data/visitors.json
chmod 664 data/visitors.json
```

---

## 📞 Quick Commands Reference

### Check if Node.js is Running
```bash
ps aux | grep node
```

### View Application Logs
```bash
tail -f logs/app.log
# OR
tail -f ~/public_html/logs/error.log
```

### Restart Application
```bash
# Method 1: Passenger restart
touch tmp/restart.txt

# Method 2: Kill and restart (if using PM2)
pm2 restart app

# Method 3: Via cPanel
# Application Manager → Restart button
```

### Test API Locally on Server
```bash
curl http://localhost:3000/api/visitor-count
```

---

## ✅ Deployment Complete Checklist

After deployment, verify:

- [ ] Website loads: https://www.womenaffairs.gov.ng
- [ ] No console errors in browser
- [ ] Visitor counter visible in bottom-right corner
- [ ] Counter shows a number (not blank)
- [ ] API endpoint works: `/api/visitor-count`
- [ ] API endpoint works: `/api/track-visit`
- [ ] Counter increments on new visits
- [ ] Counter doesn't increment on page refresh
- [ ] `data/visitors.json` file is created
- [ ] File has correct permissions (664)
- [ ] Mobile responsive (test on phone)

---

## 🎯 Next Steps

1. **Deploy Now:**
   - Check cPanel for auto-deployment status
   - OR manually upload files via File Manager
   - OR pull latest changes via SSH

2. **Test Thoroughly:**
   - Visit the live site
   - Check all pages with visitor counter
   - Test on different devices/browsers

3. **Monitor:**
   - Check `data/visitors.json` periodically
   - Monitor for any errors in logs
   - Verify counter is incrementing correctly

---

## 📚 Additional Resources

- **cPanel Documentation:** https://docs.cpanel.net/
- **Node.js on cPanel:** Application Manager section
- **Git Deployment:** Git Version Control section
- **File Manager:** For manual uploads

---

**Need Help?**
- Check cPanel error logs
- Review deployment history in cPanel
- Contact your hosting provider if Node.js app won't start

---

**Created:** October 6, 2025  
**Last Updated:** October 6, 2025  
**Status:** Ready for Production Deployment 🚀
