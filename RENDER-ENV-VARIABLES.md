# 🌐 Render.com - Environment Variables Setup

Complete list of environment variables to add in Render.com dashboard.

---

## 📋 Step-by-Step: Add Environment Variables

1. Go to **Render Dashboard** → Your Web Service
2. Click **"Environment"** tab (left sidebar)
3. Click **"+ Add Environment Variable"** for each variable below

---

## ✅ Required Environment Variables

### Application Settings

| Variable Name | Value | Notes |
|---------------|-------|-------|
| `APP_NAME` | `Attendance System` | Your app name |
| `APP_ENV` | `production` | Always use `production` for live apps |
| `APP_KEY` | `base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=` | Your generated key (✅ already done!) |
| `APP_DEBUG` | `false` | Important: Must be `false` for production |
| `APP_URL` | **(Leave empty or see below)** | Will be set automatically by Render |

### Supabase Database Settings

| Variable Name | Value | Notes |
|---------------|-------|-------|
| `DB_CONNECTION` | `pgsql` | PostgreSQL connection |
| `DB_HOST` | `db.gaceopxhzgdxjjbflozf.supabase.co` | Your Supabase host |
| `DB_PORT` | `5432` | PostgreSQL port |
| `DB_DATABASE` | `postgres` | Default Supabase database |
| `DB_USERNAME` | `postgres` | Supabase default user |
| `DB_PASSWORD` | `lA24fOnhlqTlQkHv` | Your Supabase password |
| `DB_SSLMODE` | `require` | **Required** for Supabase |

### Other Settings

| Variable Name | Value | Notes |
|---------------|-------|-------|
| `CACHE_DRIVER` | `file` | File-based caching |
| `QUEUE_CONNECTION` | `sync` | Synchronous queue |
| `SESSION_DRIVER` | `file` | File-based sessions |
| `LOG_CHANNEL` | `stack` | Logging channel |
| `LOG_LEVEL` | `error` | Log level for production |

---

## 🔗 About APP_URL

### Option 1: Leave Empty (Recommended for First Deployment)

**Leave `APP_URL` empty initially** - Render will handle it automatically.

**OR** add a placeholder:
```
APP_URL=https://your-app-name.onrender.com
```

### Option 2: Wait for Actual URL

After you deploy:
1. Render will give you a URL like: `https://attendance-system-xxxx.onrender.com`
2. Copy that URL
3. Go back to **Environment** tab
4. Update `APP_URL` with your actual URL:
   ```
   APP_URL=https://attendance-system-xxxx.onrender.com
   ```
5. Restart the service

---

## 📝 Complete Copy-Paste Ready Variables

Copy these exactly into Render's environment variables:

```env
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

**Note**: `APP_URL` is left empty - you'll update it after deployment.

---

## 🚀 After Deployment - Update APP_URL

1. **Deploy your app** (with APP_URL empty)
2. **Find your Render URL:**
   - In Render dashboard → Your service
   - It will be something like: `https://attendance-system-xxxx.onrender.com`
3. **Update APP_URL:**
   - Go to **Environment** tab
   - Find `APP_URL` variable
   - Update value to your actual URL
   - Click **Save Changes**
4. **Restart service** (or redeploy)

---

## ✅ Checklist

Before deploying:
- [ ] All variables added to Render Environment tab
- [ ] APP_KEY set correctly
- [ ] Supabase credentials added (DB_HOST, DB_PASSWORD, etc.)
- [ ] APP_DEBUG set to `false`
- [ ] APP_URL left empty (or placeholder)

After deployment:
- [ ] App deployed successfully
- [ ] Got actual Render URL
- [ ] Updated APP_URL with actual URL
- [ ] Service restarted
- [ ] App working correctly

---

## 🎯 Quick Answer to Your Question

**"Do I paste https://your-app.onrender.com?"**

**Answer:** 
- ❌ **NO** - that's just a placeholder
- ✅ **YES** - but only **AFTER** deployment when you get your **actual URL**

**Steps:**
1. First deployment: Leave `APP_URL` **empty**
2. After Render gives you the URL: Update `APP_URL` with your **actual URL** (e.g., `https://attendance-system-abc123.onrender.com`)

---

**✅ All your environment variables are ready! Just add them to Render dashboard!**
