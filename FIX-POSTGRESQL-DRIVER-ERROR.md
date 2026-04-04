# 🔧 Fix PostgreSQL Driver Error on Render

**Error:** `could not find driver (Connection: pgsql)`

This means PHP PostgreSQL extension is missing. I've fixed it!

---

## ✅ Fix Applied

I've updated the `Dockerfile` to install PostgreSQL PHP extensions:
- `pdo_pgsql` - PostgreSQL PDO driver
- `pgsql` - PostgreSQL extension
- `libpq-dev` - PostgreSQL development libraries

**This fix has been pushed to GitHub and Render will auto-deploy.**

---

## ⏳ Wait for Render to Redeploy

1. **Go to Render Dashboard** → Your Service
2. **Watch the deployment** (takes 2-5 minutes)
3. **Wait for "Live" status**

---

## ✅ After Deployment, Run Commands

Once Render finishes deploying, go to **Shell** tab and run:

### Step 1: Run Migrations (Correct Syntax)
```bash
php artisan migrate --force
```
**Note:** No space between `--` and `force`!

### Step 2: Run Seeder
```bash
php artisan db:seed --force
```

### Step 3: Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize
```

---

## 🔍 Verify PostgreSQL Extension

**After redeploy, verify in Shell:**
```bash
php -m | grep pgsql
```

**Should show:**
```
pdo_pgsql
pgsql
```

If not, PostgreSQL extensions aren't installed yet (wait for deploy).

---

## ✅ What Was Fixed

**Before:**
- Dockerfile only installed `pdo_sqlite`
- Missing PostgreSQL drivers

**After:**
- Dockerfile now installs `pdo_pgsql` and `pgsql`
- PostgreSQL/Supabase connections will work

---

## 📋 Correct Commands (No Spaces!)

**✅ Correct:**
```bash
php artisan migrate --force
php artisan db:seed --force
```

**❌ Wrong (has space):**
```bash
php artisan migrate -- force  # Wrong!
php artisan db:seed -- force   # Wrong!
```

---

**🎯 Wait for Render to redeploy the fix, then run migrations with correct syntax!**
