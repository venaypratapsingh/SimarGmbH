# 🔧 Fix CSS Not Loading on Render

**Your app is live but CSS isn't loading! Here's how to fix it:**

---

## ✅ Quick Fixes

### Fix 1: Set APP_URL (Most Common Issue)

1. **Go to Render Dashboard** → Your Service → **Environment** tab
2. **Find `APP_URL`** variable
3. **Update it with your Render URL:**
   ```
   APP_URL=https://simargmbh.onrender.com
   ```
   (Replace with your actual Render URL!)
4. **Save** - Service will restart automatically

---

### Fix 2: Create Storage Link (Via Shell)

1. **Go to Render Dashboard** → Your Service → **Shell** tab
2. **Run:**
   ```bash
   php artisan storage:link
   ```

---

### Fix 3: Clear Cache (Via Shell)

1. **Go to Shell** tab in Render
2. **Run:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan optimize
   ```

---

### Fix 4: Check Asset Paths

Your assets should be in:
- ✅ `public/assets/css/` - CSS files
- ✅ `public/assets/js/` - JS files
- ✅ `public/plugins/` - Plugin files

**Verify these folders exist:**
1. Check your repository - these should be committed to `public/`
2. If missing, they need to be uploaded to `public/` folder

---

## 🔍 Verify Assets Are Deployed

### Check in Render Shell:
```bash
ls -la public/assets/css/
ls -la public/plugins/
```

**If these folders are empty or missing:**
- Your assets might not be in the repository
- They need to be in `public/assets/` and `public/plugins/`

---

## 🚀 After Fixing

1. **Update APP_URL** with your Render URL
2. **Run storage:link** in Shell
3. **Clear caches** in Shell
4. **Restart service** (or wait for auto-restart)
5. **Test your app** - CSS should load now!

---

## 📋 Checklist

- [ ] APP_URL is set to your Render URL
- [ ] Storage link created (`php artisan storage:link`)
- [ ] Caches cleared
- [ ] Service restarted
- [ ] Assets exist in `public/assets/` and `public/plugins/`

---

**🎯 Start with Fix 1 (APP_URL) - that's usually the problem!**
