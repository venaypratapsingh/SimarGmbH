# Laravel Attendance Management System - Railway Deployment Guide

## 🚀 Deploy to Railway.app (Free)

### Prerequisites
- GitHub account
- Your Laravel project pushed to GitHub
- Railway account (sign up at https://railway.app)

### Step 1: Prepare Your Project

1. **Push to GitHub:**
   ```bash
   git add .
   git commit -m "Prepare for Railway deployment"
   git push origin main
   ```

2. **Verify files:**
   - ✅ `Dockerfile` (included)
   - ✅ `docker-compose.yml` (included)
   - ✅ `composer.json` (exists)
   - ✅ `package.json` (exists)

### Step 2: Create Railway Project

1. **New Project:**
   - Go to https://railway.app
   - Click "New Project"
   - Select "Deploy from GitHub repo"
   - Choose your repository

2. **Configure Service:**
   - Service Name: `attendance-app`
   - Root Directory: (leave empty)

### Step 3: Environment Variables

In Railway dashboard:

1. Click on your service
2. Go to "Variables" tab
3. Add the following:

```env
APP_NAME=Attendance Management System
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

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

### Step 4: Configure Start Command

1. Go to "Settings" tab
2. Find "Start Command"
3. Set to:
   ```
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

### Step 5: Deploy

1. Click "Deploy"
2. Watch deployment progress
3. Check logs for errors

### Step 6: Access Your Application

- Click the generated URL
- Example: `https://attendance-app.up.railway.app`

---

## 🔧 Railway Deployment Issues & Solutions

### Build Fails

**Error: `composer install` fails**
```bash
# Check PHP version compatibility
php -v

# Test locally
composer install --no-interaction
```

**Error: `npm install` fails**
```bash
# Clear node_modules and retry
rm -rf node_modules package-lock.json
npm install
```

### 500 Internal Server Error

**Check Railway logs:**
1. Dashboard → Your Service → "Logs"
2. Look for PHP errors

**Common fixes:**
1. Set correct file permissions
2. Verify all environment variables
3. Run migrations manually

### Database Not Found

SQLite on Railway is ephemeral (data lost on restart). For production:

**Option A: Use Railway PostgreSQL**
1. Dashboard → "New" → "Database" → "PostgreSQL"
2. Add to your service
3. Update environment:
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=$POSTGRES_HOST
   DB_PORT=$POSTGRES_PORT
   DB_DATABASE=$POSTGRES_DB
   DB_USERNAME=$POSTGRES_USER
   DB_PASSWORD=$POSTGRES_PASSWORD
   ```

**Option B: Use external database**
- Supabase
- Neon
- PlanetScale

---

## 📊 Railway Free Tier

### Included:
- $5 free credits/month
- 500 hours execution time
- 1GB storage per service
- SSL certificates
- Custom domains

### Limitations:
- Service sleeps after 5 min inactivity (v1)
- No persistent filesystem (use volumes)
- Limited to 2 services on free tier

---

## 🎯 Production Setup (Recommended)

### Use PostgreSQL + Persistent Storage

1. **Create PostgreSQL Database:**
   ```bash
   railway add postgresql
   ```

2. **Configure Environment:**
   ```env
   DB_CONNECTION=pgsql
   ```

3. **Add Persistent Volume:**
   - Dashboard → Service → "Volumes"
   - Create volume for storage

### Performance Optimization

Add to deployment:
```bash
php artisan optimize
php artisan config:cache
```

---

## 🔒 Security Best Practices

1. **Never commit `.env` file**
2. **Use strong APP_KEY**
3. **Enable debug only in development**
4. **Use HTTPS (automatic)**
5. **Regular database backups**

---

## 📈 Monitoring & Logging

### View Logs
```bash
# Using Railway CLI
railway logs --service attendance-app
```

### Health Checks
Railway automatically checks health endpoint. Add to `routes/web.php`:
```php
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
```

---

## 🆘 Troubleshooting

### Service Won't Start

1. Check environment variables are set
2. Verify start command is correct
3. Check port binding (use `$PORT`)

### Assets Not Loading

```bash
# In build command
npm run build

# Ensure public path is correct
```

### Slow Response Times

- Railway services sleep after inactivity
- Consider upgrade for production use
- Use CDN for static assets

---

## 📞 Support

- **Railway Docs:** https://docs.railway.app
- **Discord Community:** https://discord.gg/railway
- **GitHub Issues:** Report bugs on Railway repo

