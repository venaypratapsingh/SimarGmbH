# 🔧 Final Fix: Supabase Connection from Render

**Error:** `Network is unreachable` - Can't connect from Render to Supabase

---

## ✅ Step 1: Verify Supabase Project is ACTIVE

**CRITICAL:** Free tier projects auto-pause after inactivity!

1. **Go to**: https://app.supabase.com
2. **Select your project**
3. **Check top of dashboard:**
   - ✅ **"Active"** → Continue to Step 2
   - ⏸️ **"Paused"** → Click **"Restore"** → Wait 2-3 minutes
   - ❌ **"Inactive"** → Reactivate it

**If paused, this is 99% the problem!**

---

## ✅ Step 2: Get Fresh Connection String from Supabase

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Scroll to "Connection string"** section
3. **Click "Connection pooling" tab** (NOT "URI" tab)
4. **Copy the connection string** (should have port 6543)
5. **It should look like:**
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
   ```

---

## ✅ Step 3: Update Render Environment Variables

### Option A: Use Connection String (RECOMMENDED)

**In Render Dashboard → Your Service → Environment:**

1. **Add/Update `DATABASE_URL`:**
   ```
   DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
   ```

2. **Keep these:**
   ```
   DB_CONNECTION=pgsql
   DB_SSLMODE=require
   ```

3. **Remove or keep as backup:**
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

4. **Save** - Service will restart

### Option B: Use Individual Variables

If connection string doesn't work:

```
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

---

## ✅ Step 4: Check Supabase Network Settings

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Check "Network Restrictions"** or **"IP Allowlist"**
3. **Should be set to "Allow all"** (default)
4. **If restricted, add Render's IP ranges or set to "Allow all"**

---

## ✅ Step 5: Test Connection

**After Render restarts (wait 1-2 minutes):**

1. **Render Dashboard** → Your Service → **Shell** tab
2. **Run:**
   ```bash
   php artisan tinker
   ```
3. **Test connection:**
   ```php
   DB::connection()->getPdo();
   ```
4. **If successful** (returns PDO object), exit and run:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

---

## 🆘 If Still Not Working

### Try Direct Port 5432 (Not Pooler)

Sometimes pooler has issues. Try direct connection:

```
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

---

## 🔍 Debug Commands (In Render Shell)

```bash
# Check DNS resolution
nslookup db.gaceopxhzgdxjjbflozf.supabase.co

# Check current config
php artisan tinker
>>> config('database.connections.pgsql');
>>> exit

# Test connection
php artisan tinker
>>> try { DB::connection()->getPdo(); echo "SUCCESS"; } catch (\Exception $e) { echo $e->getMessage(); }
```

---

## 📋 Most Common Issues

1. **Supabase project is PAUSED** (90% of cases)
   - **Fix:** Restore project in Supabase dashboard

2. **Wrong port** (5%)
   - **Fix:** Use 6543 (pooler) or 5432 (direct)

3. **Network restrictions** (3%)
   - **Fix:** Check Supabase network settings

4. **IPv6 issue** (2%)
   - **Fix:** Use connection string method

---

## ✅ Action Checklist

- [ ] Check Supabase project is ACTIVE (not paused)
- [ ] Get fresh connection string from Supabase dashboard
- [ ] Update Render `DATABASE_URL` or individual variables
- [ ] Wait for Render to restart
- [ ] Test connection with `php artisan tinker`
- [ ] Run migrations if connection works

---

**🎯 FIRST: Check if Supabase project is paused - that's usually the problem!**
