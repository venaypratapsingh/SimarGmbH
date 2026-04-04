# 🔍 How to Test Database Connection in Render Shell

## ✅ Correct Way to Use Tinker

**In Render Shell:**

1. **Enter tinker:**
   ```bash
   php artisan tinker
   ```

2. **Wait for the `>>>` prompt**, then type (all on one line):
   ```php
   config('database.connections.pgsql')
   ```

3. **Press Enter**

4. **To exit tinker:**
   ```php
   exit
   ```

---

## ✅ Alternative: Use the Test Script (EASIER!)

**Instead of tinker, just run:**
```bash
php test-db-connection.php
```

This is much easier and gives you all the information at once!

---

## ✅ Test Connection Directly

**In Render Shell:**
```bash
php artisan tinker
```

**Then at the `>>>` prompt, type (all on one line):**
```php
DB::connection()->getPdo()
```

**Or with error handling:**
```php
try { DB::connection()->getPdo(); echo "SUCCESS"; } catch (\Exception $e) { echo $e->getMessage(); }
```

---

## 📋 Step-by-Step Test

**Run these commands one by one in Render Shell:**

```bash
# 1. Check PHP extensions
php -m | grep pgsql

# 2. Check environment variables
env | grep DB_

# 3. Run the test script (EASIEST!)
php test-db-connection.php

# 4. Or test in tinker
php artisan tinker
# Then at >>> prompt type:
# DB::connection()->getPdo()
```

---

**🎯 Just run `php test-db-connection.php` - it's the easiest way!**
