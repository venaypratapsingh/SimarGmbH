# 🗄️ Supabase Database Setup Guide

This guide will help you set up your Supabase PostgreSQL database for the Laravel Attendance Management System.

---

## 📋 Step 1: Create Supabase Project

1. **Sign up/Login**: Go to https://supabase.com and sign in
2. **Create New Project**:
   - Click **"New Project"** button
   - Fill in details:
     - **Name**: `attendance-system` (or your preferred name)
     - **Database Password**: Create a strong password (**SAVE THIS!**)
     - **Region**: Choose the closest region to your Azure Web App
     - **Pricing Plan**: Select **Free** (sufficient for development)

3. **Wait for provisioning**: Takes about 1-2 minutes

---

## 🔗 Step 2: Get Connection Details

### Option A: Connection String (Recommended)

1. Go to **Settings** → **Database**
2. Scroll to **Connection string** section
3. Select **URI** tab
4. Copy the connection string (looks like):
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres
   ```
5. **Parse the connection string** to get individual values:
   - **Host**: `db.xxxxx.supabase.co`
   - **Port**: `5432`
   - **Database**: `postgres`
   - **User**: `postgres`
   - **Password**: `[YOUR-PASSWORD]` (the one you set)

### Option B: Individual Settings

1. Go to **Settings** → **Database**
2. Find **Connection info** section:
   - **Host**: `db.xxxxx.supabase.co`
   - **Database name**: `postgres`
   - **Port**: `5432`
   - **User**: `postgres`
   - **Password**: (the one you set during project creation)

---

## ⚙️ Step 3: Configure Laravel Environment

### For Local Testing:

Update your `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password-here

# Optional: Use connection string instead
# DATABASE_URL=postgresql://postgres:password@db.xxxxx.supabase.co:5432/postgres

# Supabase requires SSL
DB_SSLMODE=require
```

### For Azure Web App:

Add these as **Application Settings** in Azure Portal:
- Go to your Web App → **Configuration** → **Application settings**

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password-here
DB_SSLMODE=require
```

---

## 🗃️ Step 4: Test Database Connection

### Local Testing:

```bash
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO object if connection successful

# Run migrations
php artisan migrate
```

### Via Azure:

After deployment, test via SSH/Kudu console:
```bash
php artisan migrate --force
```

---

## 📊 Step 5: Run Migrations

Once your Laravel app is deployed and connected:

### Option 1: Via Azure Portal (Kudu Console)

1. Go to: `https://your-app-name.scm.azurewebsites.net`
2. Click **Debug Console** → **SSH**
3. Navigate to `/home/site/wwwroot`
4. Run:
   ```bash
   php artisan migrate --force
   ```

### Option 2: Via Azure CLI

```bash
az webapp ssh --name your-app-name --resource-group rg-attendance-system

# Then run:
php artisan migrate --force
```

### Option 3: Via Deployment Script

Add to your deployment pipeline (GitHub Actions/Azure DevOps):

```bash
php artisan migrate --force
```

---

## 🔒 Step 6: Security Settings

### Enable SSL (Required by Supabase)

Supabase requires SSL connections. Ensure in your `.env`:

```env
DB_SSLMODE=require
```

Or if using `DATABASE_URL`:
```
postgresql://postgres:password@host:5432/postgres?sslmode=require
```

### Connection Pooling (Optional)

Supabase provides connection pooling. Use this for production:

1. Go to **Settings** → **Database** → **Connection string**
2. Select **Connection pooling** tab
3. Use port **6543** instead of **5432** for pooled connections

**Note**: Connection pooling is recommended for production to avoid connection limits.

---

## 📈 Step 7: Database Management

### Access Supabase Dashboard

1. **Table Editor**: View and edit tables directly
   - Go to **Table Editor** in Supabase dashboard

2. **SQL Editor**: Run custom SQL queries
   - Go to **SQL Editor** → **New query**

3. **Database Settings**: Manage backups, connections
   - Go to **Settings** → **Database**

### Backup Database

1. Go to **Settings** → **Database** → **Backups**
2. Supabase provides automatic daily backups (on paid plans)
3. Manual backup via SQL Editor:
   ```sql
   -- Export data (run in SQL Editor)
   ```

---

## 🔍 Step 8: Verify Setup

### Check Connection:

```bash
# Via Laravel Tinker
php artisan tinker
>>> DB::select('SELECT version()');
>>> \App\Models\User::count();
```

### Check Tables:

```bash
# List all tables
php artisan tinker
>>> DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'");
```

### Via Supabase Dashboard:

1. Go to **Table Editor**
2. You should see all Laravel tables after running migrations:
   - `users`
   - `employees`
   - `attendances`
   - `schedules`
   - etc.

---

## ⚠️ Common Issues & Solutions

### Issue 1: Connection Refused

**Solution**:
- Verify host, port, and credentials
- Check Supabase project is active
- Ensure database is not paused (free tier auto-pauses after inactivity)

### Issue 2: SSL Required Error

**Solution**:
```env
DB_SSLMODE=require
```

### Issue 3: Too Many Connections

**Solution**:
- Use connection pooling (port 6543)
- Check your application for connection leaks
- Consider upgrading Supabase plan

### Issue 4: Database Migration Errors

**Solution**:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify PostgreSQL extension is installed on Azure
- Try running migrations individually

---

## 📊 Supabase Free Tier Limits

- **Database Size**: 500 MB
- **Bandwidth**: 2 GB/month
- **API Requests**: Unlimited
- **Auto-pause**: After 1 week of inactivity (wakes on request)

**Upgrade to Pro** ($25/month) for:
- 8 GB database
- 50 GB bandwidth
- No auto-pause
- Daily backups

---

## 🔗 Useful Resources

- **Supabase Dashboard**: https://app.supabase.com
- **Supabase Docs**: https://supabase.com/docs
- **Laravel PostgreSQL Docs**: https://laravel.com/docs/database

---

## ✅ Setup Checklist

- [ ] Supabase project created
- [ ] Database password saved securely
- [ ] Connection details copied
- [ ] Environment variables configured
- [ ] SSL mode set to `require`
- [ ] Database connection tested
- [ ] Migrations run successfully
- [ ] Tables visible in Supabase dashboard

---

**🎉 Your Supabase database is now ready for your Laravel application!**
