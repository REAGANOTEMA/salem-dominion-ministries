# Salem Dominion Ministries - Final Deployment Guide

## Complete System Overview

### Frontend Architecture
- **Framework:** Vite + React + TypeScript
- **Base Path:** `/salem-dominion-ministries/`
- **API Base URL:** `http://localhost:5000` (dev) / Production URL (prod)
- **Build Output:** `frontend/dist/`

### Backend Architecture
- **API Entry Point:** `api.php` (root level)
- **API Routing:** Query string format (`?route=endpoint`)
- **Database:** MySQL with auto-detection
- **PHP Version:** 7.4+

---

## Localhost Setup (XAMPP)

### 1. Start XAMPP Services
1. Start **Apache**
2. Start **MySQL**

### 2. Database Configuration
- **Database:** `salem_dominion_ministries`
- **Username:** `root`
- **Password:** `ReagaN23#`

### 3. Start Backend Server
```bash
cd backend
npm install
npm start
# Server runs on http://localhost:5000
```

### 4. Start Frontend Dev Server
```bash
cd frontend
npm install
npm run dev
# Dev server runs on http://localhost:5173
```

### 5. Access the Application
- **Development:** http://localhost:5173/salem-dominion-ministries/
- **Production (via XAMPP):** http://localhost/salem-dominion-ministries/

---

## Production Deployment (Hosting Platform)

### Database Configuration
- **Database:** `salemdominionmin_db`
- **Username:** `salemdominionmin_db`
- **Password:** `22uHzNYEHwUsFKdVz3wT`
- **Host:** `localhost`

### Files to Upload
1. All files from root directory
2. `backend/` folder
3. `frontend/dist/` contents (or build on server)
4. `assets/` folder
5. `icons/` folder

### Environment Variables
The system auto-detects production environment and uses correct credentials.

### .htaccess Configuration
Already configured for:
- URL rewriting
- API routing
- MIME types
- Security headers
- Cache control

---

## API Configuration

### Frontend API Settings (frontend/.env)
```
VITE_API_URL=http://localhost:5000
```

For production, update to:
```
VITE_API_URL=https://salemdominionministries.com/salem-dominion-ministries/api.php
```

### API Endpoints
All endpoints use the centralized `fetchAPI` function:

```typescript
import { API_ENDPOINTS, fetchAPI } from '@/utils/api';

// GET request
const data = await fetchAPI(API_ENDPOINTS.NEWS);

// POST request
const result = await fetchAPI(API_ENDPOINTS.CONTACT, {
  method: 'POST',
  body: JSON.stringify(formData)
});
```

### Available Endpoints
- `/news` - Get news articles
- `/news/breaking` - Get breaking news
- `/gallery` - Get gallery items
- `/auth/login` - User login
- `/auth/register` - User registration
- `/events` - Get events
- `/sermons` - Get sermons
- `/prayers` - Submit prayer requests
- `/donations` - Process donations
- `/contact` - Contact form
- And more...

---

## Database Tables

The system expects these tables:
- `users` - User accounts
- `news` - News articles
- `gallery` - Gallery items
- `events` - Events
- `sermons` - Sermons
- `prayers` - Prayer requests
- `donations` - Donations
- `contact` - Contact messages
- `categories` - Categories for content

Run `backend/database_structure.sql` to create tables.

---

## Troubleshooting

### API Returns HTML Instead of JSON
1. Check backend server is running on port 5000
2. Verify `VITE_API_URL` in `frontend/.env`
3. Restart frontend dev server after changing .env

### Database Connection Failed
1. Check MySQL is running
2. Verify database credentials in `backend/.env`
3. Ensure database exists in phpMyAdmin

### 404 Errors for Assets
1. Clear browser cache (Ctrl+Shift+Delete)
2. Rebuild frontend: `npm run build`
3. Copy new assets to root `assets/` folder

### PWA Install Not Working
1. Ensure site is served over HTTPS (production)
2. Check `manifest.json` is accessible
3. Service worker is registered correctly

---

## Build and Deploy Commands

### Development
```bash
# Terminal 1 - Backend
cd backend
npm start

# Terminal 2 - Frontend
cd frontend
npm run dev
```

### Production Build
```bash
cd frontend
npm run build

# Copy assets
cp frontend/dist/assets/*.js assets/
cp frontend/dist/assets/*.css assets/
cp frontend/dist/sw.js .
```

---

## Security Notes

1. **Never commit .env files** - They contain sensitive credentials
2. **Use HTTPS in production** - Required for PWA and security
3. **Keep backend credentials secure** - Store in safe location
4. **Regular backups** - Backup database regularly

---

## Support

For issues:
1. Check browser console for errors
2. Check PHP error logs
3. Verify database connection
4. Ensure all files are uploaded correctly

---

## Final Checklist

- [ ] XAMPP Apache running
- [ ] XAMPP MySQL running
- [ ] Backend server running on port 5000
- [ ] Frontend dev server running on port 5173
- [ ] Database `salem_dominion_ministries` exists
- [ ] All tables created from `database_structure.sql`
- [ ] Browser cache cleared
- [ ] `frontend/.env` configured correctly
- [ ] `backend/.env` configured correctly

---

## Contact Information

- **Church:** Salem Dominion Ministries
- **Location:** Iganga Municipality, Uganda
- **Website:** https://salemdominionministries.com
- **Developer:** Reagan Otema