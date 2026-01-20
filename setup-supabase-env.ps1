# PowerShell script to configure .env file with Supabase credentials
# Run this script: .\setup-supabase-env.ps1

Write-Host "🔐 Setting up .env file with Supabase credentials..." -ForegroundColor Cyan

# Check if .env exists
if (Test-Path .env) {
    Write-Host "⚠️  .env file already exists. Backing up to .env.backup" -ForegroundColor Yellow
    Copy-Item .env .env.backup -Force
} else {
    # Copy from .env.example if it exists
    if (Test-Path .env.example) {
        Copy-Item .env.example .env
        Write-Host "✅ Copied .env.example to .env" -ForegroundColor Green
    } else {
        Write-Host "📝 Creating new .env file..." -ForegroundColor Yellow
    }
}

# Read .env content
$envContent = Get-Content .env -Raw -ErrorAction SilentlyContinue

if (-not $envContent) {
    # Create basic .env if it doesn't exist
    $envContent = @"
APP_NAME="Attendance Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=lA24fOnhlqTlQkHv
DB_SSLMODE=require

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
"@
    Set-Content .env $envContent
    Write-Host "✅ Created .env file with Supabase configuration" -ForegroundColor Green
} else {
    # Update existing .env with Supabase credentials
    Write-Host "📝 Updating database credentials..." -ForegroundColor Cyan
    
    # Replace database configuration
    $envContent = $envContent -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=pgsql'
    $envContent = $envContent -replace 'DB_HOST=.*', 'DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co'
    $envContent = $envContent -replace 'DB_PORT=.*', 'DB_PORT=5432'
    $envContent = $envContent -replace 'DB_DATABASE=.*', 'DB_DATABASE=postgres'
    $envContent = $envContent -replace 'DB_USERNAME=.*', 'DB_USERNAME=postgres'
    $envContent = $envContent -replace 'DB_PASSWORD=.*', 'DB_PASSWORD=lA24fOnhlqTlQkHv'
    
    # Add DB_SSLMODE if not present
    if ($envContent -notmatch 'DB_SSLMODE') {
        $envContent += "`nDB_SSLMODE=require`n"
    } else {
        $envContent = $envContent -replace 'DB_SSLMODE=.*', 'DB_SSLMODE=require'
    }
    
    Set-Content .env $envContent
    Write-Host "✅ Updated .env file with Supabase credentials" -ForegroundColor Green
}

Write-Host ""
Write-Host "✅ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "   1. Generate APP_KEY: php artisan key:generate" -ForegroundColor White
Write-Host "   2. Test connection: php artisan tinker (then run: DB::connection()->getPdo())" -ForegroundColor White
Write-Host "   3. Run migrations: php artisan migrate" -ForegroundColor White
Write-Host ""
