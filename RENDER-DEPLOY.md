# Laravel Attendance Management System - Render.com Deployment Guide

## 🚀 Deploy to Render.com (Free)

### Prerequisites
- GitHub account
- Your Laravel project pushed to GitHub

### Step 1: Prepare Your Project

1. **Push to GitHub:**
   ```bash
   git add .
   git commit -m "Prepare for Render deployment"
   git push origin main
   ```

2. **Verify files:**
   - ✅ `Dockerfile` (included)
   - ✅ `docker-compose.yml` (included)
   - ✅ `Procfile` (included)
   - ✅ `composer.json` (exists)
   - ✅ `package.json` (exists)

### Step 2: Create Render Account

1. Go to https://render.com
2. Sign up for free account
3. Connect your GitHub account

### Step 3: Create Web Service

1. **New Web Service:**
   - Click "New +" → "Web Service"
   - Connect your GitHub repository
   - Select the branch (usually "main")

2. **Configure Service:**
   ```
   Name: attendance-system
   Root Directory: (leave empty)
   Environment: PHP
   Build Command: composer install && npm install && npm run build
   Start Command: heroku-php-apache2 public/
   ```

3. **Instance Type:**
   - Select "Free" ($0/month)
   - Note: Free tier sleeps after 15 minutes of inactivity

### Step 4: Environment Variables

In Render dashboard, go to "Environment" tab and add:

```env
APP_NAME=Attendance Management System
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.onrender.com

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

**To generate APP_KEY:**
```bash
php artisan key:generate --show
```

### Step 5: Deploy

1. Click "Create Web Service"
2. Watch the build process in the logs
3. Wait for deployment to complete

### Step 6: Access Your Application

- URL: `https://attendance-system-xxxx.onrender.com`
- Check logs if you see errors

---

## 🔧 Troubleshooting Render Deployment

### Build Fails

```bash
# Check if all files are committed
git status

# Verify PHP version requirement
php -v

# Test locally first
./deploy.sh local
```

### 500 Internal Server Error

1. Check Render logs (click "Logs" tab)
2. Common issues:
   - Missing environment variables
   - File permissions
   - SQLite database not created

### Database Issues

SQLite on Render is ephemeral (files deleted on restart). For production:
- Use PostgreSQL (Render offers free tier)
- Or use external database service

---

## 📊 Render Free Tier Limitations

- **Sleep:** Free web services sleep after 15 min of inactivity
- **Bandwidth:** 100 GB/month
- **Build Time:** 500 min/month
- **SSL:** Included
- **Custom Domain:** Supported

---

## 🎯 Production Recommendations

### For Permanent Deployment (Non-free):

1. **Upgrade to Paid Plan:**
   - $25/month for Always-On service
   - No sleep issues

2. **Use PostgreSQL:**
   - Create Render PostgreSQL database
   - Update `.env`:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=your-postgres-host
     DB_PORT=5432
     DB_DATABASE=your_db
     DB_USERNAME=your_user
     DB_PASSWORD=your_pass
     ```

3. **Set Up Redis:**
   - For caching and sessions
   - Use Render Redis add-on

---

## 🔒 Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Use strong `APP_KEY`
- [ ] Enable HTTPS (automatic on Render)
- [ ] Don't commit `.env` file
- [ ] Regular backups
- [ ] Monitor logs

---

## 📈 Performance Optimization

```bash
# Before deployment, run:
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Add to Build Command:
```bash
composer install && npm install && npm run build && php artisan optimize
```

---

## 🆘 Need Help?

1. **Check Logs:** Render dashboard → Logs tab
2. **Community:** Render Discord community
3. **Documentation:** https://render.com/docs

