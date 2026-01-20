# 🚀 Azure Web App + Supabase Deployment Guide

This guide will help you deploy your Laravel Attendance Management System to Azure Web App with Supabase PostgreSQL database.

---

## 📋 Prerequisites

1. **Azure Account** - Sign up at https://azure.microsoft.com/free/
2. **Supabase Account** - Sign up at https://supabase.com (free tier available)
3. **GitHub Account** - For Azure deployment
4. **Azure CLI** (optional) - For command-line deployment

---

## 🗄️ Step 1: Set Up Supabase Database

### 1.1 Create Supabase Project

1. Go to https://supabase.com and sign in
2. Click **"New Project"**
3. Fill in the details:
   - **Name**: `attendance-system` (or your preferred name)
   - **Database Password**: Create a strong password (save this!)
   - **Region**: Choose closest to your users
   - **Pricing Plan**: Free tier is sufficient to start

4. Wait for the project to be created (takes ~2 minutes)

### 1.2 Get Database Connection Details

1. In your Supabase project dashboard, go to **Settings** → **Database**
2. Find the **Connection string** section
3. Copy the following information:
   - **Host**: `db.xxxxx.supabase.co`
   - **Database name**: `postgres`
   - **Port**: `5432`
   - **User**: `postgres`
   - **Password**: (the one you set during project creation)

### 1.3 Get Connection String (Recommended)

1. In **Settings** → **Database** → **Connection string**
2. Select **URI** tab
3. Copy the connection string (it looks like):
   ```
   postgresql://postgres:[YOUR-PASSWORD]@db.xxxxx.supabase.co:5432/postgres
   ```

### 1.4 Set Up Database Schema

You'll need to run your Laravel migrations once the app is deployed. We'll cover this in Step 4.

---

## ☁️ Step 2: Create Azure Web App

### 2.1 Create Web App via Azure Portal

1. **Login to Azure Portal**: https://portal.azure.com
2. Click **"Create a resource"** (or use the search bar)
3. Search for **"Web App"** and select it
4. Click **"Create"**

### 2.2 Configure Basic Settings

Fill in the **Basics** tab:

- **Subscription**: Select your subscription
- **Resource Group**: 
  - Click **"Create new"**
  - Name: `rg-attendance-system`
- **Name**: `attendance-system-{your-name}` (must be unique)
- **Publish**: **Code**
- **Runtime stack**: **PHP 8.2** (or highest available PHP 8.x)
- **Operating System**: **Linux** (recommended) or **Windows**
- **Region**: Choose closest to your users
- **App Service Plan**:
  - Click **"Create new"**
  - Name: `plan-attendance-system`
  - SKU and size: **Free F1** (for testing) or **Basic B1** (for production)
  - Click **"OK"**

5. Click **"Review + create"** → **"Create"**

### 2.3 Alternative: Create via Azure CLI

```bash
# Login to Azure
az login

# Create resource group
az group create --name rg-attendance-system --location eastus

# Create App Service plan (Free tier)
az appservice plan create \
  --name plan-attendance-system \
  --resource-group rg-attendance-system \
  --sku FREE \
  --is-linux

# Create Web App
az webapp create \
  --resource-group rg-attendance-system \
  --plan plan-attendance-system \
  --name attendance-system-{your-name} \
  --runtime "PHP:8.2"
```

---

## ⚙️ Step 3: Configure Application Settings

### 3.1 Set Environment Variables

1. In Azure Portal, go to your Web App
2. Navigate to **Configuration** → **Application settings**
3. Click **"+ New application setting"** and add the following:

#### Required Settings:

```env
APP_NAME=Attendance System
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.azurewebsites.net

# Supabase PostgreSQL Database
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-supabase-password-here

# Or use connection string (alternative)
# DATABASE_URL=postgresql://postgres:password@db.xxxxx.supabase.co:5432/postgres

LOG_CHANNEL=stack
LOG_LEVEL=error

CACHE_DRIVER=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

#### Important Notes:

- **APP_KEY**: Generate this locally:
  ```bash
  php artisan key:generate --show
  ```
  Copy the output and paste it as `APP_KEY` value.

- **APP_URL**: Replace with your actual Azure Web App URL

- **DB_PASSWORD**: Use the password you set when creating the Supabase project

### 3.2 Enable PostgreSQL Extension

1. In Azure Portal → Your Web App → **Configuration**
2. Under **General settings**, ensure:
   - **Always On**: **On** (for free tier, set to **Off**)
   - **PHP Version**: **8.2** (or highest available)

---

## 📦 Step 4: Deploy Your Application

### Option A: Deploy via GitHub (Recommended)

1. **Push your code to GitHub**:
   ```bash
   git add .
   git commit -m "Prepare for Azure deployment"
   git push origin main
   ```

2. **Set up Continuous Deployment**:
   - In Azure Portal → Your Web App → **Deployment Center**
   - Select **GitHub** as source
   - Authorize Azure to access your GitHub
   - Select your repository and branch
   - Click **"Save"**

3. **Configure Build Settings**:
   - In **Deployment Center** → **Settings**
   - Build provider: **GitHub Actions** (recommended) or **App Service build service**
   
   If using **GitHub Actions**, Azure will create a workflow file. Make sure it includes:
   ```yaml
   - name: Install dependencies
     run: composer install --no-dev --optimize-autoloader
   
   - name: Build assets
     run: npm install && npm run build
   ```

### Option B: Deploy via Azure CLI

```bash
# Install Azure CLI extension for webapp
az extension add --name webapp

# Deploy from local directory
cd /path/to/your/laravel/project
az webapp up --name attendance-system-{your-name} --resource-group rg-attendance-system
```

### Option C: Deploy via FTP/SFTP

1. In Azure Portal → Your Web App → **Deployment Center** → **FTPS credentials**
2. Note the FTPS endpoint and credentials
3. Use an FTP client (FileZilla, WinSCP) to upload your files

---

## 🔧 Step 5: Post-Deployment Configuration

### 5.1 Run Database Migrations

You can run migrations via Azure Portal or Azure CLI:

**Via Azure CLI**:
```bash
# SSH into your web app
az webapp ssh --name attendance-system-{your-name} --resource-group rg-attendance-system

# Once connected, run:
php artisan migrate --force
php artisan db:seed  # If you have seeders
```

**Via Kudu Console** (Browser-based):
1. Go to: `https://your-app-name.scm.azurewebsites.net`
2. Click **Debug Console** → **SSH** or **CMD**
3. Navigate to `/home/site/wwwroot`
4. Run:
   ```bash
   php artisan migrate --force
   ```

**Via Deployment Script**:
Add this to your deployment pipeline or create a custom script.

### 5.2 Set Storage Permissions

```bash
# Via SSH/Kudu
cd /home/site/wwwroot
chmod -R 775 storage bootstrap/cache
```

### 5.3 Optimize Application

```bash
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔒 Step 6: Security & SSL

1. **Enable HTTPS** (usually automatic):
   - Azure Web Apps provide free SSL via Let's Encrypt
   - Your app will be accessible at `https://your-app-name.azurewebsites.net`

2. **Update APP_URL**:
   - Ensure `APP_URL` in Application Settings uses `https://`

3. **Environment Variables**:
   - Never commit `.env` file
   - All secrets should be in Azure Application Settings

---

## 📊 Step 7: Verify Deployment

### Check Application Status

1. Visit: `https://your-app-name.azurewebsites.net`
2. Check logs:
   - Azure Portal → Your Web App → **Log stream**
   - Or: **Monitoring** → **Logs**

### Common Issues & Fixes

**500 Internal Server Error**:
```bash
# Check logs in Azure Portal → Log stream
# Common fixes:
- Verify APP_KEY is set correctly
- Check database connection string
- Ensure storage/bootstrap/cache permissions
```

**Database Connection Error**:
- Verify Supabase connection details in Application Settings
- Check Supabase project is active
- Ensure database is accessible from Azure (Supabase allows by default)

**Assets Not Loading**:
```bash
# Rebuild assets
npm run build

# Or via SSH
cd /home/site/wwwroot
npm install
npm run build
```

---

## 🚀 Continuous Deployment

### GitHub Actions Workflow (Auto-generated by Azure)

Azure will create `.github/workflows/azure-webapps-deploy.yml`. You can customize it:

```yaml
- name: Install PHP dependencies
  run: composer install --no-dev --optimize-autoloader

- name: Build assets
  run: npm install && npm run build

- name: Run migrations (optional)
  run: php artisan migrate --force
  env:
    DB_CONNECTION: ${{ secrets.DB_CONNECTION }}
    # ... other DB vars
```

---

## 💰 Cost Estimation

### Free Tier (Development/Testing):
- **Azure Web App Free (F1)**: Free
- **Supabase Free Tier**: Free (500 MB database, 2 GB bandwidth)
- **Total**: **$0/month**

### Production Tier:
- **Azure Web App Basic (B1)**: ~$13/month
- **Supabase Pro**: $25/month (8 GB database, 50 GB bandwidth)
- **Total**: ~$38/month

---

## 📝 Environment Variables Summary

| Variable | Example Value | Required |
|----------|--------------|----------|
| `APP_NAME` | `Attendance System` | Yes |
| `APP_ENV` | `production` | Yes |
| `APP_KEY` | `base64:...` | Yes |
| `APP_DEBUG` | `false` | Yes |
| `APP_URL` | `https://your-app.azurewebsites.net` | Yes |
| `DB_CONNECTION` | `pgsql` | Yes |
| `DB_HOST` | `db.xxxxx.supabase.co` | Yes |
| `DB_PORT` | `5432` | Yes |
| `DB_DATABASE` | `postgres` | Yes |
| `DB_USERNAME` | `postgres` | Yes |
| `DB_PASSWORD` | `your-password` | Yes |

---

## 🔗 Useful Links

- **Azure Portal**: https://portal.azure.com
- **Supabase Dashboard**: https://app.supabase.com
- **Azure Web App Docs**: https://docs.microsoft.com/azure/app-service/
- **Supabase Docs**: https://supabase.com/docs

---

## ✅ Deployment Checklist

- [ ] Supabase project created
- [ ] Database connection details saved
- [ ] Azure Web App created
- [ ] Application settings configured
- [ ] Code pushed to GitHub
- [ ] Deployment pipeline configured
- [ ] Database migrations run
- [ ] Application accessible via HTTPS
- [ ] Logs checked (no errors)
- [ ] Test login/functionality

---

## 🆘 Support

**Troubleshooting Steps**:
1. Check **Log stream** in Azure Portal for errors
2. Verify all environment variables are set correctly
3. Ensure Supabase project is active
4. Check Azure Web App status in portal

**Useful Commands**:
```bash
# Check Azure Web App logs
az webapp log tail --name your-app-name --resource-group rg-attendance-system

# Restart Web App
az webapp restart --name your-app-name --resource-group rg-attendance-system

# View application settings
az webapp config appsettings list --name your-app-name --resource-group rg-attendance-system
```

---

**🎉 Congratulations! Your Laravel app is now running on Azure with Supabase!**
