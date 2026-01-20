# 🔐 Your Supabase Database Configuration

**Important**: Keep this file secure and do not commit it to version control!

---

## 📋 Your Supabase Connection Details

```
Host: db.gaceopxhzgdxjjbflozf.supabase.co
Port: 5432
Database: postgres
User: postgres
Password: lA24fOnhlqTlQkHv
```

**Connection String (URI)**:
```
postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:5432/postgres
```

---

## 🖥️ Local Development (.env file)

Create a `.env` file in your project root with these settings:

```env
APP_NAME="Attendance Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Supabase PostgreSQL Database
DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require

# Or use connection string (alternative method)
# DATABASE_URL=postgresql://postgres:lA24fOnhlqTlQkHv@db.gaceopxhzgdxjjbflozf.supabase.co:5432/postgres
```

**Then run:**
```bash
# Generate application key
php artisan key:generate

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Run migrations
php artisan migrate
```

---

## ☁️ Azure Web App Configuration

Add these as **Application Settings** in Azure Portal:

1. Go to your Azure Web App → **Configuration** → **Application settings**
2. Click **"+ New application setting"** and add:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.azurewebsites.net

DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
```

**Generate APP_KEY:**
```bash
php artisan key:generate --show
```

---

## ✅ Test Connection

### Local Testing:

```bash
# Test via Artisan Tinker
php artisan tinker
>>> DB::select('SELECT version()');
>>> \DB::connection()->getPdo();

# Run migrations
php artisan migrate
```

### Azure Testing:

After deployment, via Kudu Console (`https://your-app-name.scm.azurewebsites.net`):
```bash
php artisan migrate --force
```

---

## 🔒 Security Notes

1. **Never commit `.env` file** - It's in `.gitignore`
2. **Keep password secure** - Don't share publicly
3. **Use Azure Application Settings** - Don't hardcode in code
4. **Enable SSL** - Always use `DB_SSLMODE=require` with Supabase

---

## 🆘 Troubleshooting

**Connection Failed?**
- Verify password is correct: `lA24fOnhlqTlQkHv`
- Check Supabase project is active (not paused)
- Ensure `DB_SSLMODE=require` is set

**SSL Error?**
- Add `DB_SSLMODE=require` to your .env
- Verify Supabase project allows external connections

**Migration Errors?**
- Check Laravel logs: `storage/logs/laravel.log`
- Verify PostgreSQL extension is installed (Azure should have it)

---

**✅ Ready to deploy! Use these credentials in Azure Portal Application Settings.**
