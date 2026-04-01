# Complete Fix Guide for Salem Dominion Ministries Website

## Issues Fixed

### 1. Manifest.json Warnings
- **Problem**: The manifest.json had invalid `screenshots` entries referencing non-existent files and the `shortcuts` had incorrect structure
- **Solution**: Removed screenshots, fixed shortcuts structure to include proper `url` and `icons` properties

### 2. 404 Errors for Icons and Assets
- **Problem**: Icons and assets were referenced at `/salem-dominion-ministries/icons/` but the icons directory was only in `frontend/dist/icons/`
- **Solution**: Created `icons/` directory at root level and copied all SVG icons there

### 3. JavaScript MIME Type Errors
- **Problem**: JavaScript files were being served with `text/html` MIME type instead of `application/javascript`
- **Solution**: Updated `.htaccess` and `index.php` to properly set MIME types for all file types

### 4. API Errors (Getting HTML instead of JSON)
- **Problem**: API requests were being routed to serve HTML files instead of hitting the API endpoints
- **Solution**: Completely rewrote `index.php` to properly detect and route API requests to `backend/api/index.php`

### 5. Service Worker Issues
- **Problem**: Service worker was registering but icons were missing
- **Solution**: Fixed icon paths and manifest.json configuration

## Files Modified

### Root Directory
1. **manifest.json** - Fixed structure, removed invalid screenshots, corrected shortcuts
2. **index.php** - Complete rewrite for proper routing
3. **.htaccess** - Updated for better routing and MIME types
4. **icons/** - Created directory with all icon files

### Frontend Directory
1. **frontend/public/manifest.json** - Fixed structure
2. **frontend/dist/manifest.json** - Fixed structure

### Live Directory (for hosting platform)
1. **live/manifest.json** - Fixed paths and structure
2. **live/index.html** - Fixed all asset paths
3. **live/.htaccess** - Updated for proper routing

## How to Deploy to Hosting Platform

### Option 1: Deploy the Main Directory (Recommended)
Upload the entire root directory contents to your hosting platform's public_html or htdocs directory. The site will be accessible at:
- `https://salemdominionministries.com/salem-dominion-ministries/`

### Option 2: Deploy the Live Directory
If you want a cleaner URL structure, upload the contents of the `live/` directory to a subdirectory on your hosting platform:
- Upload to: `public_html/live/`
- Access at: `https://salemdominionministries.com/salem-dominion-ministries/live/`

## Testing Your Deployment

### 1. Check Manifest.json
Open browser DevTools (F12) → Application tab → Manifest
- Should show no errors
- Icons should be visible

### 2. Check Network Tab
Open browser DevTools (F12) → Network tab
- Reload the page
- All files should load with status 200
- No 404 errors
- JavaScript files should have MIME type `application/javascript`
- JSON files should have MIME type `application/json`

### 3. Check API Endpoints
Try accessing: `https://salemdominionministries.com/salem-dominion-ministries/api/health`
- Should return JSON: `{"status": "OK", "message": "Salem Dominion Ministries API is running", ...}`

### 4. Check Service Worker
Open browser DevTools (F12) → Application tab → Service Workers
- Should show service worker registered and activated
- No errors in console

## Troubleshooting

### If you still see 404 errors:
1. Check that all files were uploaded correctly
2. Verify the `.htaccess` file was uploaded (it's hidden by default on some FTP clients)
3. Make sure your hosting platform supports PHP and has `mod_rewrite` enabled

### If API still returns HTML:
1. Check that `backend/api/index.php` exists and is uploaded
2. Verify the database configuration in `backend/config/database.php`
3. Check server error logs for PHP errors

### If manifest.json still shows warnings:
1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard reload the page (Ctrl+F5)
3. Check that manifest.json is valid JSON (use a JSON validator)

## Local Development

To run the site locally with XAMPP:
1. Make sure XAMPP is running (Apache and MySQL)
2. Access the site at: `http://localhost/salem-dominion-ministries/`
3. For the live directory version: `http://localhost/salem-dominion-ministries/live/`

## Important Notes

- The `frontend/dist/` directory contains the built React application
- The `backend/` directory contains the PHP API
- The `icons/` directory at root level contains PWA icons
- The `live/` directory is an alternative deployment option

## Next Steps

1. **Test thoroughly** on both localhost and hosting platform
2. **Clear all caches** (browser, CDN, hosting platform)
3. **Monitor error logs** on your hosting platform for any issues
4. **Consider setting up proper domain** (not subdirectory) for production

## Support

If you continue to experience issues after following this guide:
1. Check browser console for specific error messages
2. Check server error logs
3. Verify all files are uploaded correctly
4. Contact your hosting provider to ensure PHP and mod_rewrite are enabled