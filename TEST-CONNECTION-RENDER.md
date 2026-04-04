# 🔍 Test Database Connection in Render

## Method 1: Use Test Script (Easiest)

1. **In Render Shell**, run:
   ```bash
   php test-db-connection.php
   ```

This will show:
- All environment variables
- Database configuration
- PHP extensions status
- Connection test result
- Detailed error messages if connection fails

---

## Method 2: Use Tinker (What You Tried)

1. **In Render Shell**, run:
   ```bash
   php artisan tinker
   ```

2. **Then type:**
   ```php
   DB::connection()->getPdo();
   ```

3. **If you get an error, try to get more details:**
   ```php
   try { DB::connection()->getPdo(); } catch (\Exception $e) { echo $e->getMessage(); }
   ```

---

## Method 3: Check Environment Variables

**In Render Shell:**
```bash
php artisan tinker
>>> config('database.connections.pgsql');
>>> exit
```

This shows your current database configuration.

---

## Method 4: Check PHP Extensions

**In Render Shell:**
```bash
php -m | grep pgsql
```

Should show:
- `pdo_pgsql`
- `pgsql`

---

## Method 5: Test Connection with Error Handling

**In Render Shell:**
```bash
php artisan tinker
```

**Then:**
```php
try {
    $pdo = DB::connection()->getPdo();
    echo "SUCCESS! Connected to: " . DB::connection()->getDatabaseName();
} catch (\PDOException $e) {
    echo "PDO Error: " . $e->getMessage();
    echo "\nError Code: " . $e->getCode();
    if (isset($e->errorInfo)) {
        echo "\nSQL State: " . ($e->errorInfo[0] ?? 'N/A');
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

---

## 📋 What to Share

**Please run `php test-db-connection.php` and share:**
1. The full output (especially error messages)
2. Or the exact error from tinker

This will help identify the exact problem!

---

**🎯 Run `php test-db-connection.php` in Render Shell and share the output!**
