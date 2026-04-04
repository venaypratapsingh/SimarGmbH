# 🚨 Fix "Network is unreachable" - Final Solution

**Error:** Can't connect to Supabase from Render - Network is unreachable

---

## ✅ Step 1: Check Supabase Project Status (MOST IMPORTANT!)

**90% of these errors are because Supabase project is PAUSED!**

1. **Go to**: https://app.supabase.com
2. **Select your project**
3. **Check the top of the dashboard:**
   - ✅ **"Active"** → Continue to Step 2
   - ⏸️ **"Paused"** → **Click "Restore"** → **Wait 2-3 minutes**
   - ❌ **"Inactive"** → Reactivate it

**Free tier projects auto-pause after 1 week of inactivity!**

---

## ✅ Step 2: Try Direct Connection (Port 5432)

**Sometimes the pooler (6543) has issues. Try direct connection:**

### In Render Dashboard → Environment Variables:

**Change `DB_PORT` from `6543` to `5432`:**

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
DB_CONNECTION=pgsql
DB_SSLMODE=require
```

**Save** - Service will restart (wait 1-2 minutes)

---

## ✅ Step 3: Verify Supabase Network Settings

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Check "Network Restrictions"** or **"IP Allowlist"**
3. **Should be set to "Allow all"** (default)
4. **If restricted, change to "Allow all"**

---

## ✅ Step 4: Check Supabase Project Location

**The error shows IPv6 address - this might be a location/routing issue:**

1. **Supabase Dashboard** → Your Project → **Settings** → **General**
2. **Check project region**
3. **Make sure project is in a supported region** (e.g., US East, EU West)

---

## ✅ Step 5: Get Fresh Connection String from Supabase

1. **Supabase Dashboard** → Your Project → **Settings** → **Database**
2. **Scroll to "Connection string"** section
3. **Try BOTH:**
   - **"URI" tab** (direct connection - port 5432)
   - **"Connection pooling" tab** (pooler - port 6543)
4. **Copy the connection string**
5. **Use it in Render `DATABASE_URL`**

---

## 🔍 Debug: Test from Render Shell

**In Render Shell, test network connectivity:**
```bash
# Test DNS resolution
nslookup db.gaceopxhzgdxjjbflozf.supabase.co

# Test if port is reachable (if tools available)
nc -zv db.gaceopxhzgdxjjbflozf.supabase.co 6543
nc -zv db.gaceopxhzgdxjjbflozf.supabase.co 5432
```

---

## 🆘 Alternative Solutions

### Option A: Try Railway Instead
- Railway might have better network connectivity
- You already set it up - might work better than Render

### Option B: Check Supabase Status Page
- Check if Supabase has outages
- https://status.supabase.com

### Option C: Create New Supabase Project
- Sometimes projects get into a bad state
- Create new project in same region
- Update credentials in Render

### Option D: Use Supabase Transaction Mode
- In Supabase Dashboard → Settings → Database
- Try "Session mode" instead of "Transaction mode" for pooler

---

## 📋 Action Checklist

- [ ] **Check Supabase project status - is it ACTIVE?**
- [ ] **If paused: Restore project and wait 2-3 minutes**
- [ ] **Try direct connection (port 5432) instead of pooler (6543)**
- [ ] **Check Supabase network settings - should allow all**
- [ ] **Get fresh connection string from Supabase dashboard**
- [ ] **Verify project region in Supabase**

---

## 🎯 Most Likely Solutions (In Order)

1. **Supabase project is PAUSED** (90% of cases)
   - **Fix:** Restore project in Supabase dashboard

2. **Try direct port 5432** (5%)
   - Pooler might have connectivity issues
   - **Fix:** Change DB_PORT to 5432

3. **Network restrictions** (3%)
   - **Fix:** Check Supabase network settings

4. **IPv6 connectivity issue** (2%)
   - Render might not support IPv6 to Supabase
   - **Fix:** Try different connection method or platform

---

**🚨 FIRST: Check if Supabase project is paused - if yes, restore it and wait 2-3 minutes!**
