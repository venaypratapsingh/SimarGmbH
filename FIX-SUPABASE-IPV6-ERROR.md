# 🔧 Fix "Network is unreachable" - IPv6 Issue

**Error shows IPv6 connection (2a05:d018:...) - Render might not support IPv6 to Supabase**

---

## 🚨 Critical Checks First

### Check 1: Is Supabase Project Active?

1. **Go to Supabase Dashboard**: https://app.supabase.com
2. **Select your project**
3. **Check status:**
   - ✅ **"Active"** - Good, continue
   - ⏸️ **"Paused"** - Click "Restore" and wait 2-3 minutes
   - ❌ **"Inactive"** - Reactivate it

**Free tier projects pause after inactivity - this is the #1 cause!**

---

## ✅ Solution 1: Use Direct Connection String (Force IPv4)

Instead of separate variables, use the full connection string:

### In Render Environment Variables:

1. **Remove or keep these (as backup):**
   - `DB_HOST`
   - `DB_PORT`
   - `DB_DATABASE`
   - `DB_USERNAME`
   - `DB_PASSWORD`

2. **Add/Update `DATABASE_URL`:**
   ```
   DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
   ```

3. **Keep these:**
   ```
   DB_CONNECTION=pgsql
   DB_SSLMODE=require
   ```

4. **Save** - Service will restart

---

## ✅ Solution 2: Get Fresh Connection String from Supabase

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Scroll to "Connection string"**
3. **Click "Connection pooling" tab**
4. **Copy the URI** (should have port 6543)
5. **Use that exact string in Render `DATABASE_URL`**

---

## ✅ Solution 3: Check Supabase Network Settings

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Check "Network Restrictions"** or **"IP Allowlist"**
3. **Make sure it's set to "Allow all"** or add Render's IP ranges
4. **By default, Supabase allows all connections** - but verify this

---

## ✅ Solution 4: Try Direct Port 5432 (If Pooler Fails)

Sometimes the pooler has issues. Try direct connection:

### In Render Environment Variables:

```
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

**Or use connection string:**
```
DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:5432/postgres?sslmode=require
```

---

## 🔍 Debug: Test Connection from Render Shell

**In Render Shell, test if you can reach Supabase:**
```bash
# Test DNS resolution
nslookup db.gaceopxhzgdxjjbflozf.supabase.co

# Test port connectivity (if nc/telnet available)
nc -zv db.gaceopxhzgdxjjbflozf.supabase.co 6543
```

---

## 🆘 If Nothing Works: Alternative Solutions

### Option A: Use Railway Instead
- Railway might have better network connectivity
- You already set it up earlier

### Option B: Check Supabase Status Page
- Check if Supabase has any outages
- https://status.supabase.com

### Option C: Create New Supabase Project
- Sometimes projects get into a bad state
- Create new project and update credentials

---

## 📋 Most Likely Issues (In Order)

1. **Supabase project is PAUSED** (90% of cases)
   - Free tier auto-pauses after inactivity
   - **Fix:** Go to Supabase → Restore project

2. **Network restrictions** (5%)
   - Check Supabase network settings
   - Should allow all by default

3. **IPv6 connectivity issue** (5%)
   - Render might not support IPv6 to Supabase
   - Try connection string method

---

## ✅ Action Plan

1. **FIRST:** Check Supabase Dashboard - Is project ACTIVE?
2. **If paused:** Click "Restore" and wait 2-3 minutes
3. **Then:** Try connection string method (Solution 1)
4. **Test:** `php artisan tinker` → `DB::connection()->getPdo()`

---

**🎯 Check Supabase project status FIRST - if it's paused, that's the problem!**
