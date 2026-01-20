# 🔧 Fix Supabase Connection Error on Render

**Error:** `Network is unreachable` when connecting to Supabase

This is a network connectivity issue. Here's how to fix it:

---

## ✅ Fix 1: Use Supabase Connection Pooling (RECOMMENDED!)

Supabase provides a connection pooler that's more reliable. Use port **6543** instead of **5432**:

### In Render Environment Variables:

1. **Go to Render Dashboard** → Your Service → **Environment** tab
2. **Update `DB_PORT` from `5432` to `6543`:**
   ```
   DB_PORT=6543
   ```
3. **Keep everything else the same:**
   ```
   DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=lA24fOnhlqTlQkHv
   DB_SSLMODE=require
   ```
4. **Save** - Service will restart

**Port 6543 is the connection pooler - it's more reliable for cloud services like Render!**

---

## ✅ Fix 2: Check Supabase Network Settings

1. **Go to Supabase Dashboard**: https://app.supabase.com
2. **Select your project**
3. **Go to Settings** → **Database**
4. **Check "Connection Pooling"** section
5. **Enable connection pooling** if not already enabled
6. **Use the connection pooler port (6543)**

---

## ✅ Fix 3: Use Connection String with IPv4

If port 6543 doesn't work, use the connection string with specific options:

### In Render Environment Variables:

**Add or update `DATABASE_URL`:**
```
DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
```

**Or for direct connection (port 5432):**
```
DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:5432/postgres?sslmode=require
```

**Remove individual DB_ variables** (or keep them as backup):
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

**Keep:**
- `DB_CONNECTION=pgsql`
- `DB_SSLMODE=require`

---

## ✅ Fix 4: Verify Supabase Project is Active

1. **Go to Supabase Dashboard**
2. **Check your project status**
3. **Make sure it's not paused** (free tier pauses after inactivity)
4. **If paused, click "Restore" or "Resume"**

---

## 🔍 Test Connection (After Fixing)

**In Render Shell:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::select('SELECT version()');
>>> exit
```

**Should return:** PostgreSQL version without errors

---

## 📋 Recommended Solution: Use Connection Pooler (Port 6543)

**Why port 6543?**
- More reliable for cloud services
- Better connection handling
- Works better with Render's network

**Steps:**
1. Update `DB_PORT=6543` in Render Environment
2. Save and wait for restart
3. Try migrations again: `php artisan migrate --force`

---

## 🆘 If Still Not Working

### Check Supabase Settings:

1. **Supabase Dashboard** → Settings → Database
2. **Connection Pooling** → Enable if disabled
3. **Connection String** → Use port 6543 (pooler)
4. **Check if your project is active** (not paused)

### Verify Network Access:

- Supabase allows connections by default
- Render should be able to connect
- Try connection pooler port (6543) first

---

**🎯 Try Fix 1 first (Change DB_PORT to 6543) - that usually fixes connection issues on Render!**
