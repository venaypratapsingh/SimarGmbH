# 🔧 Fix 500 Error After Login on Render

**You're getting 500 error after login. Here's how to fix it:**

---

## ✅ Quick Fixes (Try These First)

### Fix 1: Run Database Migrations (CRITICAL!)

**The most common cause is missing database tables.**

1. **Go to Render Dashboard** → Your Service → **Shell** tab
2. **Run migrations:**
   ```bash
   php artisan migrate --force
   ```
3. **Run seeder to create admin user:**
   ```bash
   php artisan db:seed --force
   ```
4. **Clear all caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   php artisan optimize
   ```

---

### Fix 2: Check Render Logs (Find Exact Error)

1. **Render Dashboard** → Your Service → **Logs** tab
2. **Look for red error messages** - they tell you exactly what's wrong
3. **Copy the error message** - this will help identify the issue

---

### Fix 3: Common Issues & Solutions

#### Issue A: Role Middleware Error
**Error:** "Attempt to read property 'slug' on null" or similar

**Solution:**
1. Make sure migrations ran successfully
2. Run seeder to create roles:
   ```bash
   php artisan db:seed --force
   ```
3. Verify user has admin role:
   ```bash
   php artisan tinker
   >>> $user = \App\Models\User::where('email', 'admin@simargmbh.com')->first();
   >>> $user->roles; // Should show admin role
   >>> exit
   ```

#### Issue B: Missing Tables/Data
**Error:** "Table doesn't exist" or "Column not found"

**Solution:**
- Run migrations: `php artisan migrate --force`
- Run seeders: `php artisan db:seed --force`

#### Issue C: Session Issues
**Error:** Session errors or storage issues

**Solution:**
1. Check storage permissions (via Shell):
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```
2. Clear session files:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

#### Issue D: Missing Views or Controllers
**Error:** "View not found" or "Class not found"

**Solution:**
- This shouldn't happen if code is deployed correctly
- Check Render logs for specific missing file

---

## 🔍 Step-by-Step Debugging

### Step 1: Check Render Logs

1. **Go to Render Dashboard** → Your Service
2. **Click "Logs" tab**
3. **Look for the latest error** (usually red text)
4. **Copy the error message**

### Step 2: Verify Database Setup

**Via Render Shell:**
```bash
php artisan tinker
>>> \DB::connection()->getPdo(); // Should work
>>> \DB::table('users')->count(); // Should show at least 1
>>> \DB::table('roles')->count(); // Should show at least 2 (admin, employee)
>>> exit
```

**If tables don't exist:**
- Run migrations: `php artisan migrate --force`

### Step 3: Verify User & Role

**Via Render Shell:**
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'admin@simargmbh.com')->first();
>>> $user->name; // Should show "Admin"
>>> $user->roles; // Should show admin role
>>> $user->hasRole('admin'); // Should return true
>>> exit
```

**If user doesn't have role:**
```bash
php artisan tinker
>>> $user = \App\Models\User::where('email', 'admin@simargmbh.com')->first();
>>> $role = \App\Models\Role::where('slug', 'admin')->first();
>>> $user->roles()->sync($role->id);
>>> exit
```

---

## 🚨 Most Common Causes

1. **Migrations not run** (95% of cases)
   - **Fix:** `php artisan migrate --force`

2. **User doesn't have admin role**
   - **Fix:** Run seeder or assign role manually

3. **Cache issues**
   - **Fix:** Clear all caches

4. **Missing APP_KEY**
   - **Fix:** Verify APP_KEY is set in Render Environment variables

---

## ✅ Complete Fix Checklist

Run these in Render Shell (in order):

```bash
# 1. Run migrations
php artisan migrate --force

# 2. Run seeders (creates admin user and roles)
php artisan db:seed --force

# 3. Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Optimize
php artisan optimize

# 5. Verify user exists
php artisan tinker
>>> \App\Models\User::where('email', 'admin@simargmbh.com')->first();
>>> exit
```

---

## 📋 After Running Fixes

1. **Clear browser cache** (or use incognito)
2. **Try logging in again:**
   - Email: `admin@simargmbh.com`
   - Password: `password`
3. **Should redirect to:** `/admin` (dashboard)

---

## 🆘 Still Getting 500 Error?

1. **Check Render Logs** - Copy the exact error message
2. **Share the error** - I can help fix it
3. **Common fixes:**
   - Make sure APP_KEY is set correctly
   - Verify all environment variables are set
   - Check database connection is working
   - Ensure storage permissions are correct

---

**🎯 Start with Fix 1 (Run Migrations & Seeders) - that fixes 95% of 500 errors!**
