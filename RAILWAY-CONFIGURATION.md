# ⚙️ Railway Configuration Setup

**I've created `railway.json` for you!** This file tells Railway how to deploy your app.

---

## ✅ What Was Created

### `railway.json` File

This file configures:
- **Builder**: Uses Dockerfile for building
- **Deployment**: Uses Railway V2 runtime
- **Sleep**: App can sleep when inactive (saves resources)
- **Restart Policy**: Auto-restarts on failure

---

## 📝 Next Steps

### 1. Commit and Push `railway.json`

```bash
git add railway.json
git commit -m "Add Railway configuration"
git push origin main
```

### 2. Verify in Railway Dashboard

1. **Go to Railway Dashboard**: https://railway.app
2. **Find your project** "proud generosity"
3. **Check if it's detecting the Dockerfile**:
   - Railway should automatically detect `Dockerfile` in root
   - If using `railway.json`, it should use that config

### 3. Make Sure Variables Are Set

Go to **Variables** tab and verify all environment variables are there:
- `APP_KEY`
- `DB_CONNECTION=pgsql`
- `DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co`
- `DB_PASSWORD=lA24fOnhlqTlQkHv`
- etc.

---

## 🔧 Dockerfile Update

**I've also updated your Dockerfile** to use Railway's `$PORT` variable instead of hardcoded `8000`.

**Before:**
```dockerfile
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

**After:**
```dockerfile
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
```

This ensures Railway's dynamic port works correctly.

---

## 📋 What `railway.json` Does

```json
{
  "build": {
    "builder": "DOCKERFILE",          // Uses your Dockerfile
    "dockerfilePath": "/Dockerfile",  // Dockerfile location
    "buildEnvironment": "V3"          // Build environment version
  },
  "deploy": {
    "runtime": "V2",                  // Railway runtime version
    "numReplicas": 1,                 // One instance
    "sleepApplication": true,         // Can sleep when idle (saves money)
    "restartPolicyType": "ON_FAILURE", // Auto-restart on crash
    "restartPolicyMaxRetries": 10     // Try 10 times before giving up
  }
}
```

---

## 🚨 If "Project Not Found"

### Check These:

1. **Repository Connection:**
   - Railway → Settings → Make sure GitHub repo is connected
   - Repository name should match

2. **Service Detection:**
   - Railway should auto-detect Dockerfile
   - If not, manually create a service and select Dockerfile

3. **Branch:**
   - Make sure Railway is watching the correct branch (usually `main`)

4. **File Location:**
   - `railway.json` should be in **root directory** (same level as Dockerfile)
   - `Dockerfile` should be in **root directory**

---

## ✅ After Pushing `railway.json`

1. **Railway will automatically detect the config**
2. **It will use Dockerfile for building**
3. **Deployment should work correctly**
4. **Check deployment logs** to see progress

---

## 🔍 Verify Configuration

**Check in Railway Dashboard:**

1. Go to your service
2. **Settings** tab
3. Look for configuration options
4. Should show Dockerfile is being used

---

## 📝 Files Checklist

Make sure these files are in your repository root:

- [x] `railway.json` ✅ (just created)
- [x] `Dockerfile` ✅ (exists, updated for Railway)
- [x] `.env` (optional, not needed - use Variables tab)
- [x] `composer.json` ✅ (exists)
- [x] `package.json` ✅ (exists)

---

## 🚀 Next Steps

1. **Commit and push `railway.json`**:
   ```bash
   git add railway.json Dockerfile
   git commit -m "Configure Railway deployment"
   git push origin main
   ```

2. **Check Railway Dashboard** - It should redeploy automatically

3. **Watch deployment logs** - Make sure build succeeds

4. **Find your Railway URL** - Once deployed, get the public URL

---

**🎯 The `railway.json` file is ready! Just commit and push it!**
