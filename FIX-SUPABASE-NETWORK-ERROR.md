# 🔧 Fix "Network is unreachable" - Supabase Connection

**Error:** Can't connect to Supabase from Render

---

## ✅ Solution 1: Use Connection Pooler Port (MUST DO!)

**The error shows it's still using port 5432. Change to 6543:**

### In Render Environment Variables:

1. **Go to Render Dashboard** → Your Service → **Environment** tab
2. **Find `DB_PORT` variable**
3. **Change value from `5432` to `6543`:**
   ```
   DB_PORT=6543
   ```
4. **VERIFY `DB_SSLMODE` is set:**
   ```
   DB_SSLMODE=require
   ```
5. **Save Changes** - Service will auto-restart
6. **Wait 1-2 minutes for restart**

**Port 6543 is Supabase's connection pooler - it's required for cloud services like Render!**

---

## ✅ Solution 2: Use Connection String Instead

If changing port doesn't work, use the full connection string:

### In Render Environment Variables:

1. **Add or update `DATABASE_URL`:**
   ```
   DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
   ```

2. **Keep these variables:**
   ```
   DB_CONNECTION=pgsql
   DB_SSLMODE=require
   ```

3. **You can remove these (or keep as backup):**
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

4. **Save** - Service will restart

---

## ✅ Solution 3: Check Supabase Project Status

1. **Go to Supabase Dashboard**: https://app.supabase.com
2. **Select your project**
3. **Check if it shows "Paused" or "Active"**
4. **If paused:**
   - Click **"Restore"** or **"Resume"**
   - Wait 1-2 minutes for it to wake up
5. **Go to Settings** → **Database**
6. **Check "Connection Pooling"** is enabled
7. **Use port 6543** (connection pooler)

---

## ✅ Solution 4: Get Connection Pooler String from Supabase

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Scroll to "Connection string"** section
3. **Click "Connection pooling" tab**
4. **Copy the URI** (it will have port 6543)
5. **Use that in Render `DATABASE_URL`**

---

## 🔍 Verify Current Settings

**In Render Shell, check what's configured:**
```bash
php artisan tinker
>>> config('database.connections.pgsql');
>>> exit
```

**Should show:**
- `port` => 6543 (not 5432!)
- `sslmode` => 'require'

---

## 📋 Complete Environment Variables for Render

Make sure these are set correctly:

```
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

**OR use connection string:**
```
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
DB_SSLMODE=require
```

---

## 🚨 Important Notes

1. **Port 6543 is REQUIRED** for Render/Supabase connections
2. **Port 5432 often fails** with "Network is unreachable"
3. **Connection pooler (6543) is more reliable**
4. **Make sure Supabase project is ACTIVE** (not paused)

---

## ✅ After Fixing

1. **Wait for Render to restart** (after changing DB_PORT)
2. **Test connection:**
   ```bash
   php artisan tinker
   >>> DB::connection()->getPdo();
   ```
3. **If successful, run migrations:**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

**🎯 Change DB_PORT to 6543 in Render Environment - that's the fix!**
