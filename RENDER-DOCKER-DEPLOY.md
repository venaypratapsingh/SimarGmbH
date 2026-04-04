# 🐳 Render.com Deployment - Using Docker

**Since Render doesn't show PHP runtime, use Docker instead!**

---

## ✅ Setup Steps

### Step 1: Create Web Service
1. Go to **https://render.com**
2. Click **"New +"** → **"Web Service"**
3. Connect GitHub repo: `venaypratapsingh/SimarGmbH`
4. Select branch: `main`

### Step 2: Configure Service
1. **Name:** `simargmbh`
2. **Region:** Choose closest to you
3. **Runtime:** Select **"Docker"** ⭐
4. **Instance:** **Free** ($0/month)
5. **Dockerfile Path:** `Dockerfile` (leave as default)
6. **Root Directory:** (leave empty)
7. Click **"Create Web Service"**

### Step 3: Add Environment Variables
Go to **"Environment"** tab and add:

```
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=
APP_DEBUG=false
APP_URL=

DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Note:** Don't set Build Command or Start Command - Dockerfile handles it!

---

## 🚀 That's It!

Render will:
1. Build your Docker image
2. Run your Laravel app
3. Give you a URL like: `https://simargmbh.onrender.com`

---

## ⚠️ Important Notes

- **Runtime:** Use **Docker** (not PHP runtime)
- **No Build Command:** Dockerfile handles it
- **No Start Command:** Dockerfile CMD handles it
- **Just add environment variables** and deploy!

---

**✅ Your Dockerfile is ready - just select Docker runtime and deploy!**
