# 🚨 Fix "Network is unreachable" Error During Login

**You're getting this error when trying to login:**
```
SQLSTATE[08006] [7] connection to server at "db.gaceopxhzgdxjjbflozf.supabase.co" 
(2a05:d018:135e:1662:330e:eecc:596d:a52b), port 5432 failed: Network is unreachable
```

**This means Render cannot connect to your Supabase database at all.**

---

## 🎯 Root Cause

**The error shows:**
- Trying to connect on **port 5432** (direct connection)
- Attempting **IPv6** connection (2a05:d018:...)
- **"Network is unreachable"** = Database is offline or blocked

**Most likely causes:**
1. **Supabase project is PAUSED** (90% of cases!)
2. **IP restrictions blocking Render**
3. **Network routing issue** (IPv6 vs IPv4)

---

## ✅ Step 1: Check Supabase Project Status (MOST IMPORTANT!)

**Free tier projects auto-pause after 1 week of inactivity!**

1. **Go to:** https://app.supabase.com
2. **Select your project**
3. **Check the top of the page:**
   - **"Active"** (green) → Continue to Step 2
   - **"Paused"** (gray/red) → **RESTORE IT NOW!**

**If Paused:**
- Click **"Restore"** or **"Resume"** button
- **Wait 3-5 minutes** for it to fully wake up
- Status must show **green/ACTIVE** before testing

---

## ✅ Step 2: Check IP Restrictions in Supabase

**Supabase might be blocking Render's IP addresses:**

1. **Supabase Dashboard** → **Settings** → **Database**
2. **Find "Network Restrictions"** or **"IP Allowlist"** tab
3. **Set to "Allow all"** (remove any specific IPs)
4. **Save** → Wait 1-2 minutes

---

## ✅ Step 3: Use Connection Pooler (Port 6543)

**Supabase connection pooler is more reliable than direct connection:**

**In Render Environment Variables:**
1. **Render Dashboard** → Your Service → **Environment** tab
2. **Update these variables:**

   **Change from:**
   ```
   DB_PORT=5432
   ```

   **To:**
   ```
   DB_PORT=6543
   ```

   **Also update DATABASE_URL if you're using it:**
   ```
   DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:6543/postgres?sslmode=require
   ```

3. **Save** → Wait for redeploy

**The connection pooler (port 6543) is more stable and handles connections better.**

---

## ✅ Step 4: Force IPv4 Connection

**The error shows IPv6 connection attempt. Force IPv4:**

**Update `config/database.php`:**

**Find the `pgsql` connection array and add:**

```php
'pgsql' => [
    'driver' => 'pgsql',
    'url' => env('DATABASE_URL'),
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '5432'),
    'database' => env('DB_DATABASE', 'forge'),
    'username' => env('DB_USERNAME', 'forge'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'schema' => 'public',
    'sslmode' => env('DB_SSLMODE', 'prefer'),
    'options' => [
        // Force IPv4 connection
        PDO::ATTR_PERSISTENT => false,
    ],
],
```

**Then commit and push:**
```bash
git add config/database.php
git commit -m "Force IPv4 for PostgreSQL connection"
git push origin main
```

**Wait for Render to redeploy.**

---

## ✅ Step 5: Verify Database Connection

**Test if connection works:**

**In Render Shell:**
```bash
php artisan tinker
```

**Then:**
```php
// Test connection
try {
    \DB::connection()->getPdo();
    echo "✅ Connection successful!\n";
} catch (\Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

// Test if users table exists
try {
    $count = \DB::table('users')->count();
    echo "✅ Users table exists. Count: " . $count . "\n";
} catch (\Exception $e) {
    echo "❌ Users table error: " . $e->getMessage() . "\n";
}

exit
```

**If connection works → You can now login!**

---

## ✅ Step 6: Run Migrations (After Connection Works)

**Once connection is working, create tables:**

**In Render Shell:**
```bash
# Create all tables
php artisan migrate --force

# Create admin user and roles
php artisan db:seed --force

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan optimize
```

---

## 📋 Complete Fix Checklist

**Do these in order:**

1. **✅ Check Supabase project - is it ACTIVE?** (Most Important!)
   - If paused: Restore and wait 3-5 minutes

2. **✅ Set IP restrictions to "Allow all"** in Supabase Dashboard

3. **✅ Change DB_PORT to 6543** in Render environment variables

4. **✅ Update DATABASE_URL** to use port 6543

5. **✅ Force IPv4** in config/database.php (if needed)

6. **✅ Test connection** using tinker

7. **✅ Run migrations** once connection works

8. **✅ Try logging in** again

---

## 🆘 Still Getting "Network is unreachable"?

### Option A: Create New Supabase Project

**Sometimes projects get into a bad state:**

1. Create new project in Supabase
2. Get new credentials
3. Update Render environment variables
4. Run migrations

### Option B: Check Supabase Status

**Check if Supabase has outages:**
- https://status.supabase.com

### Option C: Try Railway Instead

**Railway might have better network connectivity:**
- Use Railway.app (you already set it up)
- Same Supabase credentials
- Might work better than Render

---

## 💡 Why This Happens During Login

**When you try to login:**
1. Laravel tries to authenticate you
2. It queries the `users` table: `SELECT * FROM users WHERE email = 'admin@simargmbh.com'`
3. This requires a database connection
4. If connection fails → **"Network is unreachable"** error

**Solution:**
- Fix database connection first
- Then login will work

---

## 🎯 Most Important Steps

1. **Check if Supabase project is ACTIVE** (not paused) - **This is #1 priority!**
2. **Use port 6543** (connection pooler) instead of 5432
3. **Set IP restrictions to "Allow all"** in Supabase

**After fixing connection, you can login successfully!**

---

**🚨 START WITH: Check Supabase Dashboard - Is your project ACTIVE or PAUSED? If paused, restore it and wait 3-5 minutes!**
