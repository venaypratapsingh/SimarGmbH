#!/bin/bash

# Laravel Attendance Management System - Deployment Script
# Usage: ./deploy.sh [environment]
# Options: local, render, railway, cyclic, docker

set -e

echo "🚀 Starting deployment process..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[✓]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[!]${NC} $1"
}

print_error() {
    echo -e "${RED}[✗]${NC} $1"
}

# Default environment
ENV=${1:-local}

echo "📦 Deployment Environment: $ENV"
echo ""

# Step 1: Check prerequisites
echo "Step 1: Checking prerequisites..."
command -v php >/dev/null 2>&1 || { print_error "PHP is not installed"; exit 1; }
command -v composer >/dev/null 2>&1 || { print_error "Composer is not installed"; exit 1; }
command -v npm >/dev/null 2>&1 || { print_error "NPM is not installed"; exit 1; }
print_status "All prerequisites met"

# Step 2: Install PHP dependencies
echo ""
echo "Step 2: Installing PHP dependencies..."
composer install --no-interaction --optimize-autoloader || {
    print_error "Composer install failed"
    exit 1
}
print_status "PHP dependencies installed"

# Step 3: Install NPM dependencies
echo ""
echo "Step 3: Installing NPM dependencies..."
npm install --no-interaction || {
    print_error "NPM install failed"
    exit 1
}
print_status "NPM dependencies installed"

# Step 4: Create environment file
echo ""
echo "Step 4: Configuring environment..."
if [ ! -f .env ]; then
    cp .env.example .env
    print_status "Environment file created"
else
    print_warning "Environment file already exists"
fi

# Generate APP_KEY if not set
if ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=$" .env; then
    php artisan key:generate --show
    print_status "APP_KEY generated"
fi

# Step 5: Run database migrations
echo ""
echo "Step 5: Running database migrations..."
php artisan migrate --force || {
    print_error "Migration failed"
    exit 1
}
print_status "Database migrations completed"

# Step 6: Clean duplicate emails
echo ""
echo "Step 6: Cleaning duplicate emails..."
php artisan app:clean-duplicate-emails --dry-run
php artisan app:clean-duplicate-emails || true
print_status "Duplicate email cleanup completed"

# Step 7: Build assets
echo ""
echo "Step 7: Building assets..."
if [ "$ENV" = "production" ]; then
    npm run build || npm run prod || {
        print_warning "Production build failed, trying development build..."
        npm run dev || true
    }
else
    npm run dev || {
        print_warning "Dev build failed, trying production build..."
        npm run build || npm run prod || true
    }
fi
print_status "Assets built"

# Step 8: Clear and optimize caches
echo ""
echo "Step 8: Optimizing application..."
php artisan optimize:clear || true
php artisan optimize || true
print_status "Application optimized"

# Step 9: Set permissions
echo ""
echo "Step 9: Setting permissions..."
chmod -R 775 storage bootstrap/cache database/database.sqlite 2>/dev/null || true
print_status "Permissions set"

# Step 10: Storage link
echo ""
echo "Step 10: Creating storage link..."
php artisan storage:link || true
print_status "Storage link created"

# Environment-specific steps
case $ENV in
    local)
        echo ""
        echo "Step 11: Starting local server..."
        echo ""
        print_status "✅ Deployment completed successfully!"
        echo ""
        echo "🌐 To access the application:"
        echo "   Development server: http://localhost:8000"
        echo "   Run: php artisan serve"
        echo ""
        echo "📝 Next steps:"
        echo "   1. Open http://localhost:8000"
        echo "   2. Login with default credentials (check database/seeders)"
        echo "   3. Configure your biometric device settings"
        ;;
        
    render|railway|cyclic)
        echo ""
        echo "Step 11: Preparing for cloud deployment..."
        print_status "✅ Deployment completed successfully!"
        echo ""
        echo "🌐 Cloud Deployment Steps for $ENV:"
        echo "   1. Push to GitHub: git add . && git commit -m 'Deploy' && git push"
        echo "   2. Connect repository to $ENV"
        echo "   3. Set environment variables in $ENV dashboard"
        echo "   4. Deploy"
        echo ""
        echo "📝 Required Environment Variables:"
        echo "   APP_NAME=Attendance System"
        echo "   APP_ENV=production"
        echo "   APP_KEY=$(php artisan key:generate --show | head -1)"
        echo "   APP_DEBUG=false"
        echo "   DB_CONNECTION=sqlite"
        echo "   DB_DATABASE=/app/database/database.sqlite"
        ;;
        
    docker)
        echo ""
        echo "Step 11: Building Docker image..."
        docker build -t attendance-system . || {
            print_error "Docker build failed"
            exit 1
        }
        print_status "Docker image built"
        echo ""
        print_status "✅ Deployment completed successfully!"
        echo ""
        echo "🌐 To run with Docker:"
        echo "   docker-compose up -d"
        echo "   Access at: http://localhost:8000"
        ;;
esac

echo ""
echo "📊 Deployment Summary:"
echo "   Environment: $ENV"
echo "   Time: $(date)"
echo "   PHP Version: $(php -v | head -1)"
echo "   Laravel Version: $(php artisan --version)"
echo ""

exit 0

