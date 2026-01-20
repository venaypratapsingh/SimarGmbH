# 📚 Deployment Documentation Index

This directory contains all deployment guides for the Laravel Attendance Management System.

---

## 🎯 Choose Your Deployment Option

### ☁️ Azure Web App + Supabase (Recommended for Production)

**Best for**: Production deployments with managed database

- ✅ **Quick Start**: [`QUICK-DEPLOY-AZURE.md`](QUICK-DEPLOY-AZURE.md) - Get started in 15 minutes
- 📖 **Complete Guide**: [`AZURE-SUPABASE-DEPLOY.md`](AZURE-SUPABASE-DEPLOY.md) - Detailed step-by-step instructions
- 🗄️ **Database Setup**: [`SUPABASE-SETUP.md`](SUPABASE-SETUP.md) - Supabase PostgreSQL configuration

**Cost**: Free tier available | Production: ~$38/month

---

### 🚂 Railway.app

**Best for**: Quick deployments with free tier

- 📖 **Guide**: [`RAILWAY-DEPLOY.md`](RAILWAY-DEPLOY.md)

**Cost**: Free tier with credits | Paid plans available

---

### 🎨 Render.com

**Best for**: Easy deployments with free subdomain

- 📖 **Guide**: [`RENDER-DEPLOY.md`](RENDER-DEPLOY.md)

**Cost**: Free tier available | Paid plans available

---

### 🐳 Docker / Self-Hosted

**Best for**: Full control over infrastructure

- 📖 **Guide**: [`HOSTING.md`](HOSTING.md)
- 🐳 **Dockerfile**: Available in root directory

**Cost**: VPS/server costs (varies)

---

### ⚡ Quick Reference

**Fastest way to deploy**: [`DEPLOY-QUICKSTART.md`](DEPLOY-QUICKSTART.md)

---

## 📋 Deployment Checklist

Before deploying, ensure:

- [ ] Code is committed to Git
- [ ] All environment variables documented
- [ ] Database migrations tested locally
- [ ] Assets compiled (`npm run build`)
- [ ] Application key generated
- [ ] Security settings configured (APP_DEBUG=false)

---

## 🔧 Configuration Files

| File | Purpose |
|------|---------|
| `.deployment` | Azure deployment configuration |
| `deploy-azure.sh` | Azure deployment preparation script |
| `Dockerfile` | Docker container configuration |
| `docker-compose.yml` | Docker orchestration |
| `Procfile` | Heroku/Render process configuration |
| `web.config` | IIS/Azure Windows configuration |

---

## 📊 Database Options

| Database | Guide | Notes |
|----------|-------|-------|
| **PostgreSQL (Supabase)** | [`SUPABASE-SETUP.md`](SUPABASE-SETUP.md) | ✅ Recommended for production |
| **MySQL** | Standard Laravel setup | See `.env.example` |
| **SQLite** | [`HOSTING.md`](HOSTING.md) | Good for development/testing |

---

## 🆘 Need Help?

1. **Check the specific deployment guide** for your chosen platform
2. **Review logs** in your hosting platform's dashboard
3. **Verify environment variables** are set correctly
4. **Check Laravel logs**: `storage/logs/laravel.log`

---

## 🎓 Deployment Guides by Complexity

### Beginner (Easiest)
1. **Render.com** - [`RENDER-DEPLOY.md`](RENDER-DEPLOY.md)
2. **Railway.app** - [`RAILWAY-DEPLOY.md`](RAILWAY-DEPLOY.md)

### Intermediate
3. **Azure + Supabase** - [`AZURE-SUPABASE-DEPLOY.md`](AZURE-SUPABASE-DEPLOY.md)

### Advanced
4. **Docker/Self-hosted** - [`HOSTING.md`](HOSTING.md)

---

## 📝 Environment Variables Reference

Common environment variables needed for all deployments:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database (choose one)
DB_CONNECTION=pgsql  # For Supabase
DB_CONNECTION=mysql  # For MySQL
DB_CONNECTION=sqlite # For SQLite

# Supabase (if using PostgreSQL)
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-password
DB_SSLMODE=require
```

---

## 🚀 Quick Deploy Commands

### Azure
```bash
az webapp up --name your-app-name --resource-group rg-attendance-system
```

### Railway
```bash
railway up
```

### Render
Deploy via GitHub integration in Render dashboard

---

**🎉 Ready to deploy? Pick a guide above and get started!**
