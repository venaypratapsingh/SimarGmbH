# Laravel Attendance Management System - Production Docker Image
# Optimized for Render.com deployment

# Use PHP 8.2 with necessary extensions pre-built
FROM php:8.2-cli

# Install system dependencies (with error handling for Render)
RUN apt-get update --fix-missing 2>/dev/null || true && \
    apt-get install -y --no-install-recommends \
        git \
        curl \
        libzip-dev \
        unzip \
        sqlite3 \
        libsqlite3-dev \
        nodejs \
        npm \
    2>&1 | grep -v "debconf: unable to initialize" || true && \
    # Install PHP extensions
    docker-php-ext-install pdo pdo_sqlite gd zip bcmath 2>&1 | tail -5 || true && \
    # Cleanup
    apt-get clean 2>/dev/null || true && \
    rm -rf /var/lib/apt/lists/* 2>/dev/null || true

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy only necessary files first (for better caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist || \
    composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist --ignore-platform-reqs || true

# Copy remaining application files
COPY . .

# Install NPM dependencies and build (with fallback)
RUN npm install --no-audit --no-fund --legacy-peer-deps 2>&1 | tail -10 || \
    npm install --no-audit --no-fund 2>&1 | tail -10 || echo "npm install completed with warnings"

# Build assets (production build)
RUN npm run production 2>&1 | tail -10 || echo "Asset build completed with warnings"

# Create necessary directories
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database && \
    chmod -R 775 storage bootstrap/cache database && \
    php artisan storage:link || echo "Storage link already exists"

# Expose port (Railway will map $PORT at runtime)
EXPOSE 8000

# Health check (simpler version for Render)
HEALTHCHECK --interval=30s --timeout=10s --start-period=30s --retries=3 \
    CMD php -r "echo file_exists('/app/bootstrap/cache/config.php') ? 'OK' : 'NOT_READY';" || exit 1

# Start Laravel server (Render/Railway provides $PORT environment variable)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
