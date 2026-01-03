# Laravel Attendance Management System - Hosting Guide

## 🚀 Quick Start (Local Development)

### Prerequisites
- PHP >= 8.1
- Composer
- Node.js & NPM
- SQLite3
- Git

### Local Setup

```bash
# 1. Install PHP dependencies
composer install

# 2. Install NPM dependencies
npm install

# 3. Create environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Clean duplicate emails (if any)
php artisan app:clean-duplicate-emails --dry-run
php artisan app:clean-duplicate-emails

# 7. Start the development server
php artisan serve

# 8. In a separate terminal, compile assets
npm run dev
```

Access at: `http://localhost:8000`

---

## 🌐 Cloud Hosting Options (Free Tier)

### Option 1: Render.com (Recommended - Free)
- **Free**: Yes (with limitations)
- **Custom Domain**: Optional (free subdomain provided)
- **PHP Support**: Yes
- **SQLite Support**: Limited (use PostgreSQL)
- **Link**: https://render.com

#### Deploy Steps:
1. Push to GitHub
2. Create new Web Service on Render
3. Connect your repository
4. Configure:
   - Build Command: `composer install && npm install && npm run build`
   - Start Command: `heroku-php-apache public/` or `php artisan serve`
5. Add environment variables
6. Deploy

### Option 2: Railway.app (Free)
- **Free**: Yes (with credits)
- **Custom Domain**: Optional
- **PHP Support**: Yes (via Docker)
- **SQLite Support**: Yes (ephemeral filesystem)
- **Link**: https://railway.app

#### Deploy Steps:
1. Push to GitHub
2. Create new project on Railway
3. Connect repository
4. Add environment variables
5. Deploy

### Option 3: Cyclic.sh (Free)
- **Free**: Yes
- **Custom Domain**: Optional
- **PHP Support**: Yes
- **SQLite Support**: Yes
- **Link**: https://cyclic.sh

### Option 4: Coolify (Self-hosted)
- **Free**: Yes (open source)
- **Custom Domain**: Yes
- **Control Panel**: Yes
- **Link**: https://coolify.io

---

## 🐳 Docker Deployment

### Dockerfile
```dockerfile
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    gd \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate key
RUN php artisan key:generate --show

# Build assets
RUN npm install && npm run build

# Expose port
EXPOSE 8000

# Start server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
```

### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/app
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/app/database/database.sqlite
    restart: unless-stopped
```

---

## 🔧 Production Configuration

### Environment Variables (.env)

```env
APP_NAME="Attendance System"
APP_ENV=production
APP_KEY=base64:YourGeneratedKeyHere
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

LOG_CHANNEL=stack
LOG_LEVEL=debug

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

### Production Optimizations

```bash
# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate storage link
php artisan storage:link
```

---

## 📋 Deployment Checklist

- [ ] All dependencies installed
- [ ] Environment variables configured
- [ ] Database migrated
- [ ] Application key generated
- [ ] Assets compiled
- [ ] Storage linked
- [ ] Caches cleared and optimized
- [ ] File permissions set (storage/ 775)
- [ ] HTTPS enabled (SSL certificate)
- [ ] Backup strategy in place

---

## 🚨 Troubleshooting

### 500 Internal Server Error
```bash
# Check permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 database/database.sqlite

# Check logs
tail -f storage/logs/laravel.log
```

### Database Connection Issues
```bash
# Verify SQLite file exists
ls -la database/database.sqlite

# Check permissions
chmod 775 database/database.sqlite
```

### Asset Not Loading
```bash
# Clear caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Rebuild assets
npm run build
```

---

## 📞 Support

For deployment assistance, please check:
1. README.md for detailed setup instructions
2. QUICK-SETUP.md for quick start guide
3. Application logs in `storage/logs/`

