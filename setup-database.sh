#!/bin/bash

echo "🚀 Setting up Laravel Attendance System Database..."

# Check if MySQL is running
if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
    echo "❌ MySQL is not running. Starting MySQL..."
    if [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        brew services start mysql
    else
        # Linux
        sudo service mysql start
    fi

    # Wait a moment for MySQL to start
    sleep 3
fi

# Check MySQL connection again
if ! mysql -u root -e "SELECT 1" >/dev/null 2>&1; then
    echo "❌ Cannot connect to MySQL. Please start MySQL manually and run this script again."
    exit 1
fi

echo "✅ MySQL is running"

# Create database and user
echo "📝 Creating database and user..."

mysql -u root << 'EOF'
-- Create database
CREATE DATABASE IF NOT EXISTS attendance_system;

-- Create user
CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'secure_password_123';

-- Grant privileges
GRANT ALL PRIVILEGES ON attendance_system.* TO 'laravel_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Show results
SELECT 'Database created successfully!' as status;
EOF

echo "✅ Database and user created"

# Create .env file with correct configuration
echo "📝 Creating .env configuration..."

cat > .env << 'EOF'
APP_NAME="Attendance Management System"
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password_123

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

echo "✅ .env file created"

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Clear and cache config
echo "🔄 Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate

# Seed database (optional)
echo "🌱 Seeding database..."
php artisan db:seed

# Clean up duplicate emails
echo "🧹 Cleaning up duplicate emails..."
php artisan app:clean-duplicate-emails --dry-run
php artisan app:clean-duplicate-emails

echo "🎉 Setup completed successfully!"
echo ""
echo "📋 Next steps:"
echo "1. Start the Laravel server: php artisan serve"
echo "2. In another terminal: npm run dev"
echo "3. Open http://localhost:8000 in your browser"
echo ""
echo "🔐 Database credentials:"
echo "Database: attendance_system"
echo "Username: laravel_user"
echo "Password: secure_password_123"
echo ""
echo "⚠️  IMPORTANT: Change the database password in production!"
EOF

# Make script executable
chmod +x setup-database.sh

echo "📄 Created setup script: setup-database.sh"
echo ""
echo "To run the complete setup, execute:"
echo "./setup-database.sh"
