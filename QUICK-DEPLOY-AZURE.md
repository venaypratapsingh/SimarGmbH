# ⚡ Quick Deploy: Azure Web App + Supabase

**Fast deployment guide - Get your app live in 15 minutes!**

---

## 🚀 Quick Steps

### 1️⃣ Create Supabase Database (5 min)

1. Go to https://supabase.com → **New Project**
2. Save your **database password**
3. Copy connection details from **Settings** → **Database**
   - Host: `db.xxxxx.supabase.co`
   - Port: `5432`
   - Database: `postgres`
   - User: `postgres`

### 2️⃣ Create Azure Web App (5 min)

1. Go to https://portal.azure.com → **Create a resource** → **Web App**
2. Fill details:
   - Name: `attendance-system-{yourname}`
   - Runtime: **PHP 8.2**
   - OS: **Linux**
   - Plan: **Free F1** (or Basic B1 for production)
3. Click **Create**

### 3️⃣ Configure Environment (3 min)

In Azure Portal → Your Web App → **Configuration** → **Application settings**:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.azurewebsites.net

DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password
DB_SSLMODE=require
```

**Generate APP_KEY**:
```bash
php artisan key:generate --show
```

### 4️⃣ Deploy Code (2 min)

**Option A: GitHub (Recommended)**
1. Push code to GitHub
2. Azure Portal → **Deployment Center** → Connect GitHub
3. Select repository → **Save**

**Option B: Azure CLI**
```bash
az webapp up --name your-app-name --resource-group rg-attendance-system
```

### 5️⃣ Run Migrations (via Kudu)

1. Go to: `https://your-app-name.scm.azurewebsites.net`
2. **Debug Console** → **SSH**
3. Run:
   ```bash
   cd /home/site/wwwroot
   php artisan migrate --force
   ```

### 6️⃣ Done! 🎉

Visit: `https://your-app-name.azurewebsites.net`

---

## 📚 Full Guides

- **Complete Guide**: See `AZURE-SUPABASE-DEPLOY.md`
- **Supabase Setup**: See `SUPABASE-SETUP.md`

---

## ⚠️ Common Issues

**500 Error?**
- Check APP_KEY is set
- Verify database credentials
- Check Log stream in Azure Portal

**DB Connection Failed?**
- Verify `DB_SSLMODE=require`
- Check Supabase project is active
- Confirm password is correct

---

**Need help?** Check the full deployment guide: `AZURE-SUPABASE-DEPLOY.md`
