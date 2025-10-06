# Visitor Counter Feature

## Overview
A visitor counter has been added to your Federal Ministry of Women Affairs website. This feature tracks and displays the total number of visits to your website in real-time.

## Features
- **Real-time tracking**: Automatically tracks each visit to your website
- **Session-based**: Only counts one visit per browser session (prevents inflated counts from page refreshes)
- **Persistent storage**: Visit data is stored in `data/visitors.json`
- **Beautiful UI**: Displays in a fixed position at the bottom-right corner with smooth animations
- **Responsive design**: Adapts to mobile, tablet, and desktop screens
- **Auto-updates**: Counter refreshes every 30 seconds to show latest count

## Files Added/Modified

### New Files Created:
1. **`js/visitor-counter.js`** - JavaScript component that handles tracking and display
2. **`css/visitor-counter.css`** - Styling for the visitor counter widget
3. **`add-visitor-counter.js`** - Utility script to add counter to all pages
4. **`data/visitors.json`** - Data file storing visitor statistics (auto-created)

### Modified Files:
1. **`server.js`** - Added API endpoints:
   - `GET /api/visitor-count` - Retrieves current visitor count
   - `POST /api/track-visit` - Tracks a new visit
2. **`index.html`** - Added visitor counter CSS, JS, and display element
3. **`departments/economic-services.html`** - Added visitor counter (example)

## How It Works

### 1. Tracking Visits
When a user visits any page with the visitor counter:
- The JavaScript checks if the visit has already been tracked in this session
- If not tracked, it sends a POST request to `/api/track-visit`
- The server increments the total visit count and saves it to `data/visitors.json`
- Session storage prevents duplicate counts from page refreshes

### 2. Displaying Count
- The counter fetches the current count from `/api/visitor-count`
- Displays the formatted number (with commas) in a styled widget
- Updates automatically every 30 seconds

### 3. Data Storage
The `data/visitors.json` file stores:
```json
{
  "totalVisits": 1234,
  "uniqueVisitors": 0,
  "lastReset": "2025-10-06T09:53:28.000Z",
  "lastVisit": "2025-10-06T09:53:28.000Z"
}
```

## Installation on Other Pages

To add the visitor counter to additional pages, add these three elements:

### 1. Add CSS (in `<head>` section):
```html
<link rel="stylesheet" href="css/visitor-counter.css">
<!-- For department pages, use: href="../css/visitor-counter.css" -->
```

### 2. Add JavaScript (before closing `</head>` or in `<head>` with defer):
```html
<script src="js/visitor-counter.js" defer></script>
<!-- For department pages, use: src="../js/visitor-counter.js" -->
```

### 3. Add Display Element (before closing `</body>`):
```html
<div id="visitor-counter"></div>
```

### Automated Installation
Run the provided script to add the counter to all department pages:
```bash
node add-visitor-counter.js
```

## Customization

### Change Position
Edit `css/visitor-counter.css`:

**Bottom-right (default):**
```css
#visitor-counter {
    position: fixed;
    bottom: 20px;
    right: 20px;
}
```

**Bottom-left:**
```css
#visitor-counter {
    position: fixed;
    bottom: 20px;
    left: 20px;
}
```

**Top bar (full width):**
Uncomment the alternative style at the bottom of `visitor-counter.css`

### Change Colors
Edit the gradient and text colors:
```css
#visitor-counter {
    background: linear-gradient(135deg, #014903 0%, #026b05 100%);
    color: white;
}

.visitor-count {
    color: #f9ca24; /* Yellow accent */
}
```

### Change Update Frequency
Edit `js/visitor-counter.js`, line 20:
```javascript
// Update every 30 seconds (30000 ms)
setInterval(() => this.updateDisplay(), 30000);

// Change to 60 seconds:
setInterval(() => this.updateDisplay(), 60000);
```

## API Endpoints

### GET /api/visitor-count
Returns the current visitor statistics.

**Response:**
```json
{
  "success": true,
  "totalVisits": 1234,
  "uniqueVisitors": 0,
  "lastReset": "2025-10-06T09:53:28.000Z",
  "lastVisit": "2025-10-06T09:53:28.000Z"
}
```

### POST /api/track-visit
Increments the visitor count.

**Response:**
```json
{
  "success": true,
  "totalVisits": 1235
}
```

## Testing

1. **Start the server:**
   ```bash
   npm start
   ```

2. **Open your website:**
   ```
   http://localhost:3000
   ```

3. **Verify the counter appears:**
   - Look for the counter widget in the bottom-right corner
   - It should display "Total Visitors: 0" (or current count)

4. **Test tracking:**
   - Open the website in a new incognito/private window
   - The count should increment by 1
   - Refresh the page - count should NOT increase (session-based)
   - Close and reopen incognito window - count should increase again

5. **Check data file:**
   ```bash
   cat data/visitors.json
   ```

## Resetting the Counter

To reset the visitor count to zero:

1. **Stop the server** (Ctrl+C)

2. **Edit or delete** `data/visitors.json`:
   ```json
   {
     "totalVisits": 0,
     "uniqueVisitors": 0,
     "lastReset": "2025-10-06T09:53:28.000Z"
   }
   ```

3. **Restart the server:**
   ```bash
   npm start
   ```

## Troubleshooting

### Counter not appearing
- Check browser console for JavaScript errors
- Verify `visitor-counter.css` and `visitor-counter.js` are loaded
- Ensure `<div id="visitor-counter"></div>` exists in HTML

### Count not incrementing
- Check if server is running (`npm start`)
- Verify API endpoints are accessible: `http://localhost:3000/api/visitor-count`
- Check server console for errors
- Ensure `data` directory has write permissions

### Counter shows "0" always
- Check if `data/visitors.json` exists and is readable
- Verify server has permission to write to `data` directory
- Check server logs for file system errors

## Browser Compatibility
- ✅ Chrome/Edge (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance
- Minimal impact: ~2KB CSS, ~3KB JavaScript
- API calls: 1 POST on page load, 1 GET every 30 seconds
- No external dependencies (uses native Fetch API)

## Future Enhancements
Possible improvements:
- Track unique visitors using cookies or localStorage
- Add daily/weekly/monthly statistics
- Create an admin dashboard to view analytics
- Add visitor location tracking (with consent)
- Export visitor data to CSV
- Add charts and graphs for visualization

## Support
For issues or questions, check:
- Server console logs
- Browser developer console
- Network tab for API request/response
- File permissions on `data` directory

---

**Created:** October 6, 2025  
**Version:** 1.0.0  
**Author:** Federal Ministry of Women Affairs IT Team
