# Git Deployment Fix Commands

## Problem
You have uncommitted local changes to `admin/api/media.php` that are preventing the pull operation.

## Solution Options

### Option 1: Commit Your Changes (Recommended)
If you want to keep your changes:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Add your changes
git add admin/api/media.php

# Commit your changes
git commit -m "Fix media API connection issues and add diagnostic tools"

# Now pull the latest changes
git pull origin main

# Push your changes
git push origin main
```

### Option 2: Stash Your Changes (Temporary)
If you want to temporarily save changes and pull clean:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Stash your changes
git stash push -m "Media API fixes"

# Pull the latest changes
git pull origin main

# Later, apply your stashed changes
git stash pop

# Commit and push if needed
git add admin/api/media.php
git commit -m "Reapply media API fixes"
git push origin main
```

### Option 3: Discard Your Changes (If changes are not needed)
If you want to discard your local changes and use the remote version:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Discard local changes
git checkout -- admin/api/media.php

# Now pull the latest changes
git pull origin main
```

### Option 4: Force Pull (Not Recommended - Use with Caution)
If you want to overwrite local changes completely:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Force pull to overwrite local changes
git fetch --all
git reset --hard origin/main

# This will DELETE your local changes to admin/api/media.php
```

## cPanel File Manager Method

If you're using cPanel File Manager:

1. **Backup your changes**: Download `admin/api/media.php` file
2. **Delete the file**: Remove `admin/api/media.php` from server
3. **Pull latest**: Use Git Version Control in cPanel to pull
4. **Re-upload if needed**: Upload your modified file if you still want your changes

## Quick Commands for SSH/Terminal

```bash
# Check what changes you have
git status

# See exactly what changed
git diff admin/api/media.php

# Quick commit and pull (if you want to keep changes)
git add admin/api/media.php && git commit -m "Media API fixes" && git pull
```

## Recommendation

**Option 1 (Commit)** is recommended if your media API fixes are working and you want to keep them.

**Option 2 (Stash)** is good for temporarily saving changes while you pull updates.

Choose the option that best fits your needs!
