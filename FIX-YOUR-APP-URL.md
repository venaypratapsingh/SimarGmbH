# 🔧 Fix CSS/JS - Set APP_URL Correctly

**Your Render URL:** `https://simargmbh-1.onrender.com/`

---

## ✅ STEP 1: Set APP_URL in Render

1. **Go to Render Dashboard**: https://dashboard.render.com
2. **Click your service** (`simargmbh` or similar)
3. **Go to "Environment" tab** (left sidebar)
4. **Find `APP_URL` variable** (or create it if it doesn't exist)
5. **Set the value to:**
   ```
   https://simargmbh-1.onrender.com
   ```
   **IMPORTANT:** No trailing slash!
6. **Click "Save Changes"**
7. **Service will auto-restart**

---

## ✅ STEP 2: Clear All Caches (Via Shell)

1. **Go to Render Dashboard** → Your Service → **"Shell" tab**
2. **Run these commands one by one:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   php artisan optimize
   ```

---

## ✅ STEP 3: Verify APP_URL

**In Shell, test:**
```bash
php artisan tinker
>>> config('app.url')
```

**Should return:** `https://simargmbh-1.onrender.com`

If it returns something else, `APP_URL` is not set correctly.

---

## ✅ STEP 4: Test Asset URLs

**In Shell (still in tinker):**
```bash
>>> asset('assets/css/style.css')
```

**Should return:** `https://simargmbh-1.onrender.com/assets/css/style.css`

If it returns a wrong URL, `APP_URL` needs to be fixed.

---

## 🚀 After These Steps

1. **Wait for service to restart** (if you updated APP_URL)
2. **Clear all caches** (via Shell)
3. **Open your site:** https://simargmbh-1.onrender.com/
4. **Press F12** → Console tab
5. **Check for CSS/JS errors**

**CSS and JS should load now!**

---

## 🔍 If Still Not Working

### Check Browser Console (F12)
- Look for 404 errors
- Check what URL it's trying to load for CSS/JS
- If URLs are wrong, `APP_URL` is incorrect

### Common Issues:
1. **APP_URL has trailing slash** ❌ `https://simargmbh-1.onrender.com/` 
   - **Fix:** Remove it ✅ `https://simargmbh-1.onrender.com`
   
2. **APP_URL is empty or wrong** ❌
   - **Fix:** Set to your Render URL exactly

3. **Cache not cleared** ❌
   - **Fix:** Run all clear commands in Shell

---

**🎯 Set APP_URL to `https://simargmbh-1.onrender.com` (no slash) and clear caches - that will fix it!**
