# 🚂 Deploy to Railway.app with Supabase (Alternative to Azure)

**Railway is another great free alternative if Render doesn't work for you!**

---

## ✅ Prerequisites

- ✅ Your Supabase database is connected
- ✅ Your `.env` file has Supabase credentials

---

## 🚀 Quick Deployment Steps

### Step 1: Push to GitHub

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

---

### Step 2: Create Railway Account

1. Go to **https://railway.app**
2. Click **"Start a New Project"**
3. Sign up with GitHub (easiest)

---

### Step 3: Create New Project

1. Click **"New Project"**
2. Select **"Deploy from GitHub repo"**
3. Choose your repository: `SimarGmbH`

---

### Step 4: Configure Environment Variables

1. Click on your service
2. Go to **"Variables"** tab
3. Click **"+ New Variable"** and add:

#### Application Settings:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=
```

#### Supabase Database (Your Credentials):

```env
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

#### Other Settings:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

### Step 5: Configure Start Command

1. Go to **"Settings"** tab
2. Find **"Start Command"** section
3. Set to:
   ```
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

**Alternative (if Apache available):**
```
heroku-php-apache2 public/
```

---

### Step 6: Deploy

1. Railway will auto-deploy on code push
2. Watch deployment progress in **"Deployments"** tab
3. Wait for build to complete (first build: 5-10 minutes)

---

### Step 7: Generate APP_KEY

After deployment:

1. Go to **"Variables"** tab
2. Click on service name → **"View Logs"** or use **"Deploy Logs"**
3. Or use Railway CLI (if installed):
   ```bash
   railway run php artisan key:generate --show
   ```
4. Copy the generated key
5. Update `APP_KEY` in **"Variables"**
6. Redeploy or restart service

---

### Step 8: Run Migrations

**Via Railway CLI:**
```bash
railway run php artisan migrate --force
```

**Or via deploy script** (add to package.json):
```json
{
  "scripts": {
    "postdeploy": "php artisan migrate --force"
  }
}
```

---

### Step 9: Update APP_URL

1. Copy your Railway URL (e.g., `https://attendance-app.up.railway.app`)
2. Update `APP_URL` in **"Variables"** tab
3. Service will auto-restart

---

## ✅ Your App is Live!

Visit your Railway URL: `https://your-app.up.railway.app`

---

## 🔧 Railway Configuration Files

### Option: Create `railway.json` in project root:

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer install --no-dev --optimize-autoloader && npm install && npm run build"
  },
  "deploy": {
    "startCommand": "php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

---

## 📊 Railway Free Tier

**Includes:**
- $5 free credits/month
- 500 hours execution time
- SSL certificates
- Custom domains

**Limitations:**
- Service may sleep after inactivity (v1 platform)
- Credits can run out (but regenerates monthly)

---

## 🔧 Troubleshooting

### Build Fails?

**Check:**
- Railway dashboard → **"Deploy Logs"**
- Verify `composer.json` and `package.json` are correct
- Check PHP version requirements

### 500 Error?

**Common fixes:**
1. Verify APP_KEY is set
2. Check database credentials
3. Run migrations
4. Check logs in Railway dashboard

### Database Connection Failed?

**Verify Supabase credentials:**
- Host: `db.gaceopxhzgdxjjbflozf.supabase.co`
- Password: `lA24fOnhlqTlQkHv`
- SSL Mode: `require`

---

## ✅ Deployment Checklist

- [ ] Code pushed to GitHub
- [ ] Railway account created
- [ ] Project created and connected to GitHub
- [ ] Environment variables added (Supabase credentials)
- [ ] Start command configured
- [ ] APP_KEY generated and set
- [ ] Migrations run
- [ ] APP_URL updated
- [ ] App accessible and working

---

**🎉 Your app is now live on Railway with Supabase!**
