# ☁️ Azure Web App - Environment Variables Configuration

Copy and paste these environment variables into Azure Portal **Application Settings**.

---

## 🔑 Your Configuration Values

### Supabase Database Credentials:
```
Host: db.gaceopxhzgdxjjbflozf.supabase.co
Port: 5432
Database: postgres
User: postgres
Password: lA24fOnhlqTlQkHv
```

---

## 📋 Application Settings for Azure Portal

Go to: **Azure Portal → Your Web App → Configuration → Application settings**

Add these settings (click "+ New application setting" for each):

| Name | Value | Notes |
|------|-------|-------|
| `APP_NAME` | `Attendance System` | |
| `APP_ENV` | `production` | |
| `APP_KEY` | `base64:...` | **Generate this first** (see below) |
| `APP_DEBUG` | `false` | Important for production |
| `APP_URL` | `https://your-app-name.azurewebsites.net` | Replace with your actual URL |
| `DB_CONNECTION` | `pgsql` | PostgreSQL connection type |
| `DB_HOST` | `db.gaceopxhzgdxjjbflozf.supabase.co` | Your Supabase host |
| `DB_PORT` | `5432` | PostgreSQL port |
| `DB_DATABASE` | `postgres` | Default Supabase database |
| `DB_USERNAME` | `postgres` | Supabase default user |
| `DB_PASSWORD` | `lA24fOnhlqTlQkHv` | Your Supabase password |
| `DB_SSLMODE` | `require` | **Required for Supabase** |

---

## 🔑 Generate APP_KEY

**Important**: Generate your APP_KEY before deploying:

```bash
# Run locally
php artisan key:generate --show

# Copy the output (looks like: base64:...)
# Paste it as the APP_KEY value in Azure
```

---

## 📝 Quick Copy-Paste (After generating APP_KEY)

Replace `YOUR_APP_KEY_HERE` with your generated key:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=YOUR_APP_KEY_HERE
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

---

## ✅ Verification

After setting these variables:

1. **Save** the configuration in Azure Portal
2. **Restart** your Web App
3. Test connection via Kudu Console:
   - Go to: `https://your-app-name.scm.azurewebsites.net`
   - SSH → Run: `php artisan migrate --force`

---

## 🔒 Security Reminders

- ✅ Never commit `.env` file (it's in `.gitignore`)
- ✅ Use Azure Application Settings (not hardcoded values)
- ✅ Keep `APP_DEBUG=false` in production
- ✅ Use `DB_SSLMODE=require` for Supabase connections

---

**🎯 Ready to deploy! Set these in Azure Portal and you're good to go!**
