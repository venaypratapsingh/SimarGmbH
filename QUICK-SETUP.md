# 🚀 Quick Setup Guide - Laravel Attendance System

## Choose Your Setup Method:

### Option 1: SQLite (Easiest - Recommended)
```bash
./setup-sqlite.sh
```

### Option 2: MySQL (Full Setup)
```bash
./setup-database.sh
```

### Option 3: Manual Setup

#### Step 1: Configure Database
Edit your `.env` file:

**For SQLite:**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/Users/venaypratapsingh/Desktop/Simar gmbh/database/database.sqlite
```

**For MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password_123
```

#### Step 2: Generate App Key
```bash
php artisan key:generate
```

#### Step 3: Run Migrations
```bash
php artisan migrate
```

#### Step 4: Clean Duplicates
```bash
php artisan app:clean-duplicate-emails --dry-run
php artisan app:clean-duplicate-emails
```

#### Step 5: Start Server
```bash
php artisan serve
```

## Troubleshooting:

### "Access denied for user 'root'@'localhost'"
- Use SQLite instead (easiest)
- Or create a new MySQL user with proper permissions

### "Connection refused"
- Make sure MySQL/MariaDB is running
- Check if the database server is accessible

### "Database not found"
- Run migrations: `php artisan migrate`
- Or create the database manually

## Files Created:
- ✅ `setup-database.sh` - MySQL setup script
- ✅ `setup-sqlite.sh` - SQLite setup script
- ✅ `app/Console/Commands/CleanDuplicateEmails.php` - Duplicate cleanup command
- ✅ Fixed `app/Models/User.php` - Role authorization bug

## What's Fixed:
1. ✅ User role authorization methods
2. ✅ Database connection issues
3. ✅ Duplicate email cleanup
4. ✅ Server error 500 resolved

## Next Steps:
1. Run one of the setup scripts
2. Start the development server
3. Test the application

---
**Need help?** The setup scripts will handle everything automatically! 🎉
