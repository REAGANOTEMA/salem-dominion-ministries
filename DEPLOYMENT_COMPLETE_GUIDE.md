
 # Salem Dominion Ministries - Complete Deployment Guide

## 🎉 All Features Implemented Successfully!

This guide covers the complete deployment of Salem Dominion Ministries website with all requested features.

---

## ✅ Completed Features

### 1. **Unified Database Schema**
- Created `backend/database/UNIFIED_COMPLETE_SCHEMA.sql`
- All 26+ tables including new pastor booking system
- Google Meet integration tables
- Pastor availability scheduling

### 2. **Book a Call with Pastor**
- New page: `/book-pastor-call`
- Google Meet integration
- Pastor availability calendar
- Smart scheduling system
- Booking reference generation
- Email confirmations

### 3. **Navigation Updates**
- Added "Book Pastor Call" to main navigation
- Added "Prayer Request" to main navigation
- Updated footer quick links
- Mobile-responsive navigation

### 4. **Enhanced UI/UX**
- Reduced excessive glow effects
- Smoother animations
- Professional design
- Better color contrast

### 5. **Email Configuration**
- Official email: `visit@salemdominionministries.com`
- All contact forms updated
- Removed old email addresses

---

## 🚀 Deployment Steps

### Step 1: Database Setup

```bash
# Import the unified database schema
mysql -u your_username -p < backend/database/UNIFIED_COMPLETE_SCHEMA.sql
```

**Or using phpMyAdmin:**
1. Open phpMyAdmin
2. Create database `salem_dominion_ministries`
3. Import `backend/database/UNIFIED_COMPLETE_SCHEMA.sql`

### Step 2: Backend Configuration

1. Update `backend/.env`:
```env
DB_HOST=localhost
DB_USER=your_db_user
DB_PASS=your_db_password
DB_NAME=salem_dominion_ministries
```

2. Install backend dependencies:
```bash
cd backend
npm install
```

### Step 3: Frontend Configuration

1. Install frontend dependencies:
```bash
cd frontend
npm install
```

2. Build for production:
```bash
npm run build
```

### Step 4: Hosting Setup

#### For cPanel/Shared Hosting:

1. **Upload Files:**
   - Upload `frontend/dist/*` contents to `public_html/`
   - Upload `backend/` folder to `public_html/backend/`

2. **Database:**
   - Create MySQL database in cPanel
   - Import `UNIFIED_COMPLETE_SCHEMA.sql`
   - Update `backend/.env` with database credentials

3. **Configure .htaccess:**
   ```apache
   # Enable Rewrite Engine
   RewriteEngine On
   
   # Redirect to HTTPS
   RewriteCond %{HTTPS} off
   RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
   
   # Handle React Router
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ /index.html [QSA,L]
   ```

#### For VPS/Cloud Hosting:

1. **Install Dependencies:**
```bash
# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install MySQL
sudo apt-get install mysql-server

# Install Nginx
sudo apt-get install nginx
```

2. **Configure Nginx:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com;
    
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    
    root /var/www/salem-dominion-ministries/frontend/dist;
    index index.html;
    
    location / {
        try_files $uri $uri/ /index.html;
    }
    
    location /backend {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

3. **Start Backend Service:**
```bash
cd /var/www/salem-dominion-ministries/backend
npm install
pm2 start server.js --name salem-backend
pm2 save
pm2 startup
```

### Step 5: SSL Certificate

Use Let's Encrypt for free SSL:
```bash
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

---

## 📧 Email Configuration

### Set Official Email:
All emails now use: `visit@salemdominionministries.com`

### Configure Email Sending (Optional):

1. **Using PHPMailer:**
```php
// backend/config/mail.php
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.your-hosting.com';
$mail->SMTPAuth = true;
$mail->Username = 'visit@salemdominionministries.com';
$mail->Password = 'your-email-password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
$mail->setFrom('visit@salemdominionministries.com', 'Salem Dominion Ministries');
```

2. **Using SendGrid (Recommended):**
```bash
npm install @sendgrid/mail
```

```javascript
// backend/utils/email.js
const sgMail = require('@sendgrid/mail');
sgMail.setApiKey(process.env.SENDGRID_API_KEY);

async function sendEmail(to, subject, html) {
  await sgMail.send({
    to,
    from: 'visit@salemdominionministries.com',
    subject,
    html,
  });
}
```

---

## 🎯 Testing Checklist

### Before Going Live:

- [ ] Database imported successfully
- [ ] All tables created
- [ ] Backend API responding
- [ ] Frontend builds without errors
- [ ] All pages load correctly
- [ ] Navigation links work
- [ ] Forms submit successfully
- [ ] Email confirmations work
- [ ] Mobile responsive
- [ ] SSL certificate installed
- [ ] Images load correctly
- [ ] Google Meet links generate
- [ ] Pastor booking system works

### Test Key Features:

1. **Book Pastor Call:**
   - Navigate to `/book-pastor-call`
   - Fill out form
   - Verify booking reference generated
   - Check email confirmation

2. **Prayer Request:**
   - Navigate to `/prayer-request`
   - Submit prayer request
   - Verify submission success

3. **Navigation:**
   - Test all navbar links
   - Test all footer links
   - Test mobile menu

---

## 🔧 Troubleshooting

### Database Connection Issues:
```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u your_user -p -h localhost salem_dominion_ministries
```

### Frontend Build Errors:
```bash
# Clear cache and rebuild
cd frontend
rm -rf node_modules package-lock.json
npm install
npm run build
```

### Backend API Issues:
```bash
# Check logs
cd backend
npm install
node server.js

# Or with PM2
pm2 logs salem-backend
```

### Permission Issues:
```bash
# Set correct permissions
sudo chown -R www-data:www-data /var/www/salem-dominion-ministries
sudo chmod -R 755 /var/www/salem-dominion-ministries
```

---

## 📱 PWA Setup

The website is a Progressive Web App. Users can:

1. **Install on Mobile:**
   - Visit the website
   - Tap "Add to Home Screen"
   - App installs like native app

2. **Install on Desktop:**
   - Look for install icon in address bar
   - Click "Install"
   - App appears in applications menu

---

## 🎨 Customization

### Change Colors:
Edit `frontend/tailwind.config.ts`:
```typescript
theme: {
  extend: {
    colors: {
      gold: '#f59e0b',
      navy: '#1e293b',
      // Add your colors
    }
  }
}
```

### Update Logo:
Replace `frontend/src/assets/logo.jpeg` with your logo file.

### Modify Content:
- Edit pages in `frontend/src/pages/`
- Update navigation in `frontend/src/components/Navbar.tsx`
- Change footer in `frontend/src/components/Footer.tsx`

---

## 📊 Monitoring

### Set up Google Analytics:
Add to `frontend/index.html`:
```html
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GA_MEASUREMENT_ID');
</script>
```

### Monitor Uptime:
Use services like UptimeRobot or Pingdom to monitor your website.

---

## 🔄 Updates & Maintenance

### Regular Updates:
```bash
# Update dependencies
npm audit fix
npm update

# Rebuild frontend
npm run build

# Restart backend
pm2 restart salem-backend
```

### Backup Database:
```bash
# Create backup
mysqldump -u your_user -p salem_dominion_ministries > backup_$(date +%Y%m%d).sql

# Restore from backup
mysql -u your_user -p salem_dominion_ministries < backup_20260331.sql
```

---

## 📞 Support

### Official Contact:
- **Email:** visit@salemdominionministries.com
- **Phone:** +256 753 244480
- **WhatsApp:** https://wa.me/256753244480

### Developer Support:
- **WhatsApp:** https://wa.me/256772514889
- **Website:** https://www.ctiauganda.com

---

## 🎉 Go Live!

Once all tests pass:

1. Update DNS to point to your hosting
2. Wait for DNS propagation (24-48 hours)
3. Test on live domain
4. Announce launch to congregation
5. Share on social media

**Your website is now live with all features working perfectly!** ✨

---

## 📋 Quick Reference

### Important URLs:
- **Homepage:** https://salemdominionministries.com/
- **Book Pastor Call:** https://salemdominionministries.com/book-pastor-call
- **Prayer Request:** https://salemdominionministries.com/prayer-request
- **Admin Dashboard:** https://salemdominionministries.com/admin

### Important Files:
- **Database Schema:** `backend/database/UNIFIED_COMPLETE_SCHEMA.sql`
- **Frontend Build:** `frontend/dist/`
- **Backend API:** `backend/server.js`
- **Environment Config:** `backend/.env`

### Important Commands:
```bash
# Build frontend
cd frontend && npm run build

# Start backend
cd backend && npm start

# Import database
mysql -u user -p < backend/database/UNIFIED_COMPLETE_SCHEMA.sql

# Check status
pm2 status
```

---

**Last Updated:** March 31, 2026
**Version:** 2.0.0 - Complete Edition