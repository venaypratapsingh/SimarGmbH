# 📋 Complete Railway Environment Variables List

**All variables you need to add in Railway → Variables tab**

---

## ✅ Essential Variables (Must Have)

Add these first - your app won't work without them:

```
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=
APP_DEBUG=false
APP_URL=
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## ⚙️ Optional Variables (Has Defaults)

These have default values in `config/attendance.php`, so you can skip them unless you want custom values:

```
ATTENDANCE_CLEARANCE_TIME=23:50
ATTENDANCE_LATE_THRESHOLD=15
ATTENDANCE_OVERTIME_THRESHOLD=30
```

**Default values:**
- `ATTENDANCE_CLEARANCE_TIME` → `23:50` (default)
- `ATTENDANCE_LATE_THRESHOLD` → `15` minutes (default)
- `ATTENDANCE_OVERTIME_THRESHOLD` → `30` minutes (default)

**Only add these if you want different values!**

---

## 🚨 Important: Railway Doesn't Read .env Files!

**Railway ONLY reads from:**
- ✅ **Variables tab** in Railway dashboard

**Railway does NOT read:**
- ❌ `.env` file in your repository
- ❌ `.env.example` file
- ❌ Any files in your code

---

## 📝 How to Add in Railway

1. Go to **Railway Dashboard** → Your Service → **"Variables"** tab
2. Click **"+ New Variable"** for each variable
3. Enter **Variable Name** and **Value**
4. Click **"Add"**

**Example:**
- Variable Name: `APP_KEY`
- Value: `base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=`

---

## ✅ Minimum Required Variables

If you're in a hurry, add at least these:

1. `APP_KEY` - Critical!
2. `DB_CONNECTION=pgsql`
3. `DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co`
4. `DB_PASSWORD=lA24fOnhlqTlQkHv`
5. `DB_SSLMODE=require`

The attendance variables can be skipped - defaults will work fine!

---

## 🔍 Verify Variables Are Set

1. Go to **Variables** tab
2. Scroll through the list
3. Make sure all essential variables are there
4. Check that values are correct (no typos)

---

**🎯 Remember: Add variables in Railway Variables tab, NOT in .env files!**
