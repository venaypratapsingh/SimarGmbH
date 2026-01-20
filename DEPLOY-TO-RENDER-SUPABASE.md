# 🚀 Deploy to Render.com with Supabase (Easy Alternative to Azure)

**Since Azure is denying access, Render.com is the easiest free alternative!**

---

## ✅ Prerequisites (Already Done!)

- ✅ Your Supabase database is connected
- ✅ Your `.env` file has Supabase credentials

---

## 🚀 Step-by-Step Deployment

### Step 1: Push Code to GitHub (2 minutes)

If you haven't already:

```bash
# Make sure you're in the project directory
cd C:\Users\vpsra\OneDrive\Desktop\SimarGmbH

# Check git status
git status

# Add all files (if needed)
git add .

# Commit
git commit -m "Prepare for Render deployment with Supabase"

# Push to GitHub
git push origin main
```

**If you don't have a GitHub repository:**
1. Go to https://github.com
2. Create a new repository
3. Push your code:
   ```bash
   git remote add origin https://github.com/yourusername/attendance-system.git
   git push -u origin main
   ```

---

### Step 2: Create Render Account (1 minute)

1. Go to **https://render.com**
2. Click **"Get Started for Free"**
3. Sign up with:
   - **GitHub** (easiest - connect your GitHub account)
   - Or email signup

---

### Step 3: Create Web Service (3 minutes)

1. In Render dashboard, click **"New +"** → **"Web Service"**

2. **Connect Repository:**
   - Select **"Public Git repository"** or connect your GitHub
   - Choose your repository: `SimarGmbH` (or your repo name)
   - Select branch: `main`

3. **Configure Service:**
   ```
   Name: attendance-system
   Region: Select closest to you (Oregon, Frankfurt, etc.)
   Branch: main
   Root Directory: (leave empty)
   Runtime: PHP
   Build Command: composer install --no-dev --optimize-autoloader && npm install && npm run build
   Start Command: heroku-php-apache2 public/
   ```

4. **Instance Type:**
   - Select **"Free"** ($0/month)
   - ⚠️ Note: Free tier sleeps after 15 minutes of inactivity (first request may be slow)

5. **Click "Create Web Service"** (don't deploy yet!)

---

### Step 4: Configure Environment Variables (3 minutes)

**Before deploying, add your Supabase credentials:**

1. In your service page, go to **"Environment"** tab (left sidebar)

2. Click **"Add Environment Variable"** and add each of these:

#### Required Variables:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=
```

#### Your Supabase Database Variables:

```env
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

#### Other Variables:

```env
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Important Notes:**
- Leave `APP_KEY` empty for now (we'll generate it after deployment)
- Leave `APP_URL` empty - Render will set it automatically

3. **Click "Save Changes"**

---

### Step 5: Generate APP_KEY

**You need to generate APP_KEY before deploying:**

**Option A: Generate locally (if you have PHP):**
```bash
php artisan key:generate --show
```
Copy the output and paste it as `APP_KEY` value in Render.

**Option B: Generate after first deployment:**
- Deploy first (will fail, but that's okay)
- Then generate key via Render Shell (see Step 7)

---

### Step 6: Deploy! (5-10 minutes)

1. Go to **"Manual Deploy"** tab (or wait for auto-deploy)
2. Click **"Deploy latest commit"**
3. Watch the build logs
4. Wait for deployment to complete (first build takes 5-10 minutes)

---

### Step 7: Generate APP_KEY (if not done yet)

1. After deployment, go to **"Shell"** tab in Render dashboard
2. Run:
   ```bash
   php artisan key:generate --show
   ```
3. Copy the output
4. Go back to **"Environment"** tab
5. Update `APP_KEY` with the generated value
6. **Redeploy** (or restart service)

---

### Step 8: Run Database Migrations

1. Go to **"Shell"** tab in Render dashboard
2. Run:
   ```bash
   php artisan migrate --force
   ```

This will create all your database tables in Supabase!

---

### Step 9: Update APP_URL

1. Copy your Render URL (e.g., `https://attendance-system-xxxx.onrender.com`)
2. Go to **"Environment"** tab
3. Update `APP_URL` with your actual URL
4. Restart service or redeploy

---

## ✅ Your App is Live!

Visit: `https://attendance-system-xxxx.onrender.com`

---

## 🔧 Troubleshooting

### Build Fails?

**Check logs:**
- Render dashboard → Your service → **"Logs"** tab
- Look for error messages

**Common fixes:**
1. **Missing APP_KEY**: Generate and add it
2. **Build timeout**: Free tier has limits, wait and retry
3. **Missing dependencies**: Check `composer.json` and `package.json`

### 500 Internal Server Error?

**Check:**
1. APP_KEY is set correctly
2. Database credentials are correct
3. Migrations ran successfully
4. Check logs: **"Logs"** tab in Render

**Via Shell:**
```bash
php artisan config:clear
php artisan cache:clear
```

### Database Connection Error?

**Verify:**
1. Supabase project is active (not paused)
2. Credentials are correct:
   - Host: `db.gaceopxhzgdxjjbflozf.supabase.co`
   - Password: `lA24fOnhlqTlQkHv`
   - SSL Mode: `require`

**Test connection in Shell:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### Service Won't Start?

**Check:**
- Start command is correct: `heroku-php-apache2 public/`
- All environment variables are set
- Check **"Logs"** for startup errors

---

## 📊 Render Free Tier

### What's Included:
- ✅ Free hosting (sleeps after 15 min inactivity)
- ✅ Free SSL certificate
- ✅ Free subdomain (`your-app.onrender.com`)
- ✅ 100 GB bandwidth/month
- ✅ 750 hours runtime/month

### Limitations:
- ⚠️ Services sleep after 15 minutes of inactivity
- First request after sleep takes 30-60 seconds to wake
- Build time: 500 minutes/month

---

## 🚀 Upgrade Options (Optional)

### Always-On Service ($7/month):
- Never sleeps
- Faster response times
- Better for production

### Custom Domain (Free):
1. Go to **"Settings"** → **"Custom Domains"**
2. Add your domain
3. Update DNS records as instructed

---

## ✅ Deployment Checklist

- [ ] Code pushed to GitHub
- [ ] Render account created
- [ ] Web service created
- [ ] Environment variables added (with Supabase credentials)
- [ ] APP_KEY generated and set
- [ ] Service deployed successfully
- [ ] Migrations run (`php artisan migrate --force`)
- [ ] APP_URL updated with Render URL
- [ ] Application accessible and working
- [ ] Test login/functionality

---

## 📞 Need Help?

1. **Render Logs**: Dashboard → Your service → **"Logs"** tab
2. **Shell Access**: Dashboard → Your service → **"Shell"** tab
3. **Render Docs**: https://render.com/docs
4. **Community**: Render Discord community

---

## 🎯 Quick Reference

**Your Supabase Credentials:**
```
Host: db.gaceopxhzgdxjjbflozf.supabase.co
Port: 5432
Database: postgres
User: postgres
Password: lA24fOnhlqTlQkHv
```

**Important Render Settings:**
- Build Command: `composer install --no-dev --optimize-autoloader && npm install && npm run build`
- Start Command: `heroku-php-apache2 public/`
- Runtime: PHP

---

**🎉 Ready to deploy? Follow the steps above and your app will be live in 15 minutes!**
