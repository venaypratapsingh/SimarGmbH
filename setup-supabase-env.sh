#!/bin/bash

# Setup .env file with Supabase credentials
# This script creates a .env file from .env.example with your Supabase credentials

echo "🔐 Setting up .env file with Supabase credentials..."

# Check if .env already exists
if [ -f .env ]; then
    echo "⚠️  .env file already exists. Backing up to .env.backup"
    cp .env .env.backup
fi

# Copy .env.example to .env
if [ -f .env.example ]; then
    cp .env.example .env
    echo "✅ Copied .env.example to .env"
else
    echo "❌ .env.example not found. Creating new .env file..."
    # Create basic .env if .env.example doesn't exist
    cat > .env << 'EOF'
APP_NAME="Attendance Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require
EOF
fi

# Update Supabase credentials
echo "📝 Updating Supabase database credentials..."

# Use sed to update credentials (works on Linux/macOS)
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    sed -i '' 's/DB_HOST=.*/DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co/' .env
    sed -i '' 's/DB_PASSWORD=.*/DB_PASSWORD=lA24fOnhlqTlQkHv/' .env
    sed -i '' 's/DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
    sed -i '' 's/DB_SSLMODE=.*/DB_SSLMODE=require/' .env || echo "DB_SSLMODE=require" >> .env
else
    # Linux
    sed -i 's/DB_HOST=.*/DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co/' .env
    sed -i 's/DB_PASSWORD=.*/DB_PASSWORD=lA24fOnhlqTlQkHv/' .env
    sed -i 's/DB_CONNECTION=.*/DB_CONNECTION=pgsql/' .env
    sed -i 's/DB_SSLMODE=.*/DB_SSLMODE=require/' .env || echo "DB_SSLMODE=require" >> .env
fi

echo "✅ .env file configured with Supabase credentials"

# Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force 2>/dev/null || echo "⚠️  Could not generate APP_KEY automatically. Run: php artisan key:generate"
else
    echo "✅ APP_KEY already set"
fi

echo ""
echo "✅ Setup complete!"
echo ""
echo "📋 Next steps:"
echo "   1. Review .env file: cat .env"
echo "   2. Test database connection: php artisan tinker (then: DB::connection()->getPdo())"
echo "   3. Run migrations: php artisan migrate"
echo ""
