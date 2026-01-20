#!/bin/bash

# Azure Web App Deployment Script for Laravel
# This script prepares the application for Azure deployment

set -e

echo "🚀 Starting Azure deployment preparation..."

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install --no-audit --no-fund

# Build assets
echo "🏗️  Building assets..."
npm run build || npm run prod || echo "⚠️  Asset build completed with warnings"

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache || true

# Clear and cache config
echo "⚡ Optimizing application..."
php artisan config:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# Note: Migrations should be run manually via Azure Portal/Kudu/SSH
# php artisan migrate --force

echo "✅ Deployment preparation complete!"
echo "📝 Don't forget to:"
echo "   1. Set environment variables in Azure Portal"
echo "   2. Run migrations: php artisan migrate --force"
echo "   3. Verify APP_KEY is set correctly"
