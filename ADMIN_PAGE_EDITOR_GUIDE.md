# 📝 Enhanced Page Editor Guide

## Overview
The FMWA Admin Panel now includes a powerful, organized page editor with visual selection panels for all website content.

## ✨ New Features

### 1. **Organized Categories**
Pages are grouped into logical sections:

- **📄 Main Pages** (4 pages)
  - Home Page - Main landing page
  - About Us - Ministry information
  - Mandate - Ministry mandate
  - Organogram - Organizational structure

- **🏢 Departments** (Auto-detected)
  - All department pages from `/departments` directory
  - Visual cards with building icons

- **🤝 Services** (Auto-detected)
  - All service pages from `/services` directory
  - Cards with helping-hands icons

- **📰 Special Pages**
  - Press releases
  - Announcements
  - Custom content pages

- **🧩 Components** (Auto-detected)
  - Header, Footer, Navigation
  - Reusable includes from `/includes` directory

### 2. **Visual Card Interface**
Each page is displayed as an interactive card with:
- **Icon** - Visual identifier for page type
- **Name** - Clear page title
- **Description** - Purpose/context of the page
- **Hover Effect** - Interactive feedback
- **Selection State** - Highlighted when selected

### 3. **Tab Navigation**
- Switch between categories with one click
- Shows count of pages in each category
- Smooth transitions between tabs
- Active tab highlighting with gradient

### 4. **Smart Search**
- Real-time search across all pages
- Searches both page names and file paths
- Instant filtering without page reload
- Works across all categories

### 5. **Two-Step Edit Process**
1. **Select** - Click a page card to select it
2. **Edit** - Click "Edit This Page" button to load editor
3. Provides clear visual feedback of selection
4. Prevents accidental edits

### 6. **Enhanced Editor Tools**
- **Back to Selection** - Return to page browser
- **Save Changes** - Save with Ctrl+S shortcut
- **Reload** - Refresh from file
- **Preview** - Open page in new tab
- **Status Indicator** - Shows current state

## 🎯 How to Use

### Quick Start
1. Navigate to **Admin → Page Editor**
2. Browse categories using tabs at the top
3. Use search box to find specific pages
4. Click on a page card to select it
5. Click "Edit This Page" button
6. Make your changes in the code editor
7. Press Ctrl+S or click "Save Changes"
8. Click "Back to Selection" to choose another page

### Best Practices
- **Always save before switching pages**
- **Use Preview to verify changes**
- **Check Status indicator for save confirmation**
- **Backups are created automatically**
- **Use search for quick access**

## 📊 Page Statistics

The interface automatically displays:
- Total pages in each category
- Visual organization of content
- Easy access to all editable files

## 🔒 Safety Features

### Automatic Protection
- **Unsaved Changes Warning** - Alerts before leaving
- **Automatic Backups** - Files backed up before saving
- **Error Handling** - Graceful failure recovery
- **Session Management** - Requires authentication

### Edit Confirmation
- Selected page clearly highlighted
- Confirmation before loading into editor
- Status updates during operations
- Error messages for issues

## 💡 Tips & Tricks

### Navigation
- Use **Tab** to switch categories quickly
- **Search** works across all categories
- **Back button** returns to selection
- **Preview** opens in new tab (won't lose changes)

### Editing
- **Ctrl+S** - Quick save
- **Syntax Highlighting** - PHP, HTML, CSS, JavaScript
- **Line Numbers** - Easy reference
- **Code Folding** - Collapse sections
- **Auto-indent** - Clean formatting

### Organization
- **Main Pages** - Core website pages
- **Departments** - Individual department content
- **Services** - Program and service pages
- **Components** - Reusable elements (headers, footers)
- **Special Pages** - One-off content

## 🎨 Visual Elements

### Color Coding
- **Purple Gradient** - Selected/Active items
- **Blue Border** - Hover state
- **Green Alert** - Confirmation messages
- **Gray** - Inactive/Default state

### Icons
- 🏠 Home - Main pages
- 🏢 Building - Departments
- 🤝 Hands - Services
- 📰 Newspaper - Press/News
- 🧩 Puzzle - Components

## 📱 Responsive Design

Works perfectly on:
- **Desktop** - Full grid layout
- **Tablet** - Adjusted columns
- **Mobile** - Stacked cards

## 🔧 Technical Details

### File Detection
- Automatically scans directories
- Lists all `.php` files
- Excludes system files
- Real-time updates

### Editor Features
- CodeMirror with Dracula theme
- PHP syntax highlighting
- Multiple language support
- Keyboard shortcuts
- Line wrapping
- Bracket matching

### API Integration
- RESTful page API
- JSON responses
- Error handling
- Status feedback

## 📈 Performance

- **Fast Loading** - Minimal page weight
- **Instant Search** - Client-side filtering
- **Smooth Animations** - CSS transitions
- **Efficient Rendering** - Optimized JavaScript

## 🆘 Troubleshooting

### Common Issues

**Cards not showing:**
- Check if files exist in directories
- Verify permissions on folders
- Refresh the page

**Save not working:**
- Check file permissions
- Verify database connection
- Look for JavaScript errors

**Editor not loading:**
- Ensure CodeMirror libraries load
- Check browser console for errors
- Verify API endpoint is accessible

## 🚀 Future Enhancements

Potential additions:
- [ ] Bulk edit multiple pages
- [ ] Version history/rollback
- [ ] Compare versions
- [ ] Duplicate pages
- [ ] Template system
- [ ] Custom categories
- [ ] Favorites/Bookmarks
- [ ] Recent edits tracking

## 📞 Support

For help:
- Check status indicator for error messages
- Review browser console for JavaScript errors
- Contact: admin@fmwa.gov.ng

---

**Version:** 2.0.0  
**Updated:** November 2025  
**Feature:** Enhanced Visual Page Editor with Categorized Selection
