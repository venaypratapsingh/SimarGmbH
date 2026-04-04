# 🔧 How to Use Tinker Correctly

## ❌ Common Mistake

**Don't type `DB::connection()->getPdo()` in bash shell!**

You'll get: `bash: syntax error near unexpected token '-'`

---

## ✅ Correct Way

### Step 1: Enter Tinker
**In Render Shell (bash prompt), type:**
```bash
php artisan tinker
```

### Step 2: Wait for Tinker Prompt
**You should see:**
```
Psy Shell v0.12.9 (PHP 8.2.30 — cli) by Justin Hileman
>>> 
```

**Notice the `>>>` prompt - that's tinker!**

### Step 3: Type Your Command
**Now at the `>>>` prompt, type:**
```php
DB::connection()->getPdo()
```

**Press Enter**

### Step 4: Exit Tinker
**When done, type:**
```php
exit
```

**Or press `Ctrl+D`**

---

## ✅ Easier Alternative: Use Test Script

**Instead of tinker, just run this in bash shell:**
```bash
php test-db-connection.php
```

This is much easier and gives you everything at once!

---

## 📋 Quick Reference

**Bash shell prompt:** `root@...:/app#` or `$`

**Tinker prompt:** `>>>`

**You must be at `>>>` prompt to run PHP/Laravel code!**

---

**🎯 Run `php artisan tinker` first, wait for `>>>` prompt, THEN type `DB::connection()->getPdo()`**
