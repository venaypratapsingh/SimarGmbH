# ⚙️ Railway Environment Variables Setup

**Important:** Railway does **NOT** read `.env` files!

Railway uses environment variables from the **Variables tab** in the dashboard. You must set all variables there.

---

## ❌ Common Mistake

**Don't rely on:**
- ❌ `.env` file - Railway doesn't read it
- ❌ `.env.example` - Just a template for local development
- ❌ Files in your repository - Not used for deployment

**Do use:**
- ✅ **Railway Dashboard → Variables tab** - This is what Railway uses!

---

## ✅ How to Set Environment Variables in Railway

### Step 1: Open Variables Tab

1. Go to **Railway Dashboard**
2. Click on your **project**
3. Click on your **service**
4. Click **"Variables"** tab (left sidebar)

### Step 2: Add All Required Variables

Click **"+ New Variable"** for each variable below:

---

## 📋 Complete List of Variables (Copy-Paste Ready)

Add these one by one in Railway Variables tab:

### Application Settings:

```
APP_NAME
Attendance System
```

```
APP_ENV
production
```

```
APP_KEY
base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=
```

```
APP_DEBUG
false
```

```
APP_URL
(leave empty for now, or your Railway URL after you get it)
```

### Supabase Database:

```
DB_CONNECTION
pgsql
```

```
DB_HOST
db.gaceopxhzgdxjjbflozf.supabase.co
```

```
DB_PORT
5432
```

```
DB_DATABASE
postgres
```

```
DB_USERNAME
postgres
```

```
DB_PASSWORD
lA24fOnhlqTlQkHv
```

```
DB_SSLMODE
require
```

### Other Settings:

```
CACHE_DRIVER
file
```

```
QUEUE_CONNECTION
sync
```

```
SESSION_DRIVER
file
```

```
LOG_CHANNEL
stack
```

```
LOG_LEVEL
error
```

### Attendance Settings (Optional - Has Defaults):

These are optional. If not set, defaults will be used from `config/attendance.php`:
- `ATTENDANCE_CLEARANCE_TIME` defaults to `23:50`
- `ATTENDANCE_LATE_THRESHOLD` defaults to `15` (minutes)
- `ATTENDANCE_OVERTIME_THRESHOLD` defaults to `30` (minutes)

**Only add these if you want custom values:**

```
ATTENDANCE_CLEARANCE_TIME
23:50
```

```
ATTENDANCE_LATE_THRESHOLD
15
```

```
ATTENDANCE_OVERTIME_THRESHOLD
30
```

---

## 🔍 How to Verify Variables Are Set

### Check in Railway Dashboard:

1. Go to **Variables** tab
2. You should see all variables listed
3. Make sure they have values (not empty)

### Common Issues:

**Problem:** App not working, getting errors
**Solution:** Check Variables tab - make sure all variables are set

**Problem:** Database connection fails
**Solution:** Verify `DB_HOST`, `DB_PASSWORD`, `DB_SSLMODE` are correct

**Problem:** APP_KEY not set error
**Solution:** Add `APP_KEY` variable in Railway Variables tab

---

## 🚨 Important Notes

1. **Variables are case-sensitive:**
   - `APP_KEY` not `app_key`
   - `DB_HOST` not `db_host`

2. **No quotes needed:**
   - Value: `production` (not `"production"`)
   - Value: `false` (not `"false"`)

3. **Spaces matter:**
   - Don't add extra spaces before/after values

4. **After adding variables:**
   - Railway will automatically restart your service
   - Changes take effect immediately

---

## 📝 Quick Copy-Paste Format

If Railway allows bulk import, you can use this format:

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

**Note:** Some platforms allow bulk import. Otherwise, add them one by one.

---

## ✅ Checklist

Before deployment, verify:
- [ ] All variables added in Railway **Variables** tab
- [ ] `APP_KEY` is set (not empty)
- [ ] `DB_PASSWORD` is correct
- [ ] `DB_SSLMODE=require` is set
- [ ] `APP_DEBUG=false` (for production)
- [ ] All variable names are uppercase (e.g., `APP_KEY` not `app_key`)

---

## 🔧 Troubleshooting

### "APP_KEY is not set" Error

**Solution:**
1. Go to Variables tab
2. Add `APP_KEY` variable
3. Value: `base64:Aq53BZu1YDa67NGomP25SIUWOIhVtefd1RCwIg13nFQ=`
4. Save

### Database Connection Fails

**Solution:**
1. Check Variables tab
2. Verify `DB_HOST` = `db.gaceopxhzgdxjjbflozf.supabase.co`
3. Verify `DB_PASSWORD` = `lA24fOnhlqTlQkHv`
4. Verify `DB_SSLMODE` = `require`

### App Still Reading .env.example

**This shouldn't happen on Railway!** Railway only reads from Variables tab.

If you see this error:
1. Make sure variables are set in Railway Variables tab (not just in files)
2. Redeploy the service after adding variables
3. Check logs to see which variables are missing

---

**🎯 Remember: Railway uses Variables tab, NOT .env files!**
