# PowerShell script to help generate Laravel APP_KEY
# Run this script: .\generate-app-key.ps1

Write-Host "🔑 Laravel APP_KEY Generator" -ForegroundColor Cyan
Write-Host ""

# Check for PHP installations
$phpPaths = @(
    "php",
    "C:\xampp\php\php.exe",
    "C:\wamp64\bin\php\php8.2\php.exe",
    "C:\wamp64\bin\php\php8.1\php.exe",
    "C:\laragon\bin\php\php8.2\php.exe",
    "C:\laragon\bin\php\php8.1\php.exe",
    "$env:ProgramFiles\PHP\php.exe"
)

$phpFound = $null

foreach ($phpPath in $phpPaths) {
    if ($phpPath -eq "php") {
        try {
            $null = Get-Command php -ErrorAction Stop
            $phpFound = "php"
            break
        } catch {
            continue
        }
    } else {
        if (Test-Path $phpPath) {
            $phpFound = $phpPath
            break
        }
    }
}

if ($phpFound) {
    Write-Host "✅ PHP found at: $phpFound" -ForegroundColor Green
    Write-Host ""
    Write-Host "Generating APP_KEY..." -ForegroundColor Yellow
    
    if ($phpFound -eq "php") {
        $keyOutput = & php artisan key:generate --show 2>&1
    } else {
        $keyOutput = & $phpFound artisan key:generate --show 2>&1
    }
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host ""
        Write-Host "✅ Your APP_KEY is:" -ForegroundColor Green
        Write-Host $keyOutput -ForegroundColor White -BackgroundColor DarkBlue
        Write-Host ""
        Write-Host "📋 Copy this and add to:" -ForegroundColor Cyan
        Write-Host "   1. Your .env file: APP_KEY=$keyOutput" -ForegroundColor White
        Write-Host "   2. Render/Railway environment variables" -ForegroundColor White
        Write-Host ""
        
        # Optionally update .env file
        if (Test-Path .env) {
            $update = Read-Host "Update .env file automatically? (y/n)"
            if ($update -eq "y" -or $update -eq "Y") {
                $envContent = Get-Content .env -Raw
                if ($envContent -match "APP_KEY=.*") {
                    $envContent = $envContent -replace "APP_KEY=.*", "APP_KEY=$keyOutput"
                } else {
                    $envContent = $envContent -replace "(APP_ENV=.*)", "`$1`nAPP_KEY=$keyOutput"
                }
                Set-Content .env $envContent
                Write-Host "✅ .env file updated!" -ForegroundColor Green
            }
        }
    } else {
        Write-Host "❌ Error generating key:" -ForegroundColor Red
        Write-Host $keyOutput -ForegroundColor Red
        Write-Host ""
        Write-Host "💡 Try generating manually using Method 3 in HOW-TO-GET-APP-KEY.md" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ PHP not found in common locations" -ForegroundColor Red
    Write-Host ""
    Write-Host "📝 Here are your options:" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. Install PHP:" -ForegroundColor White
    Write-Host "   - XAMPP: https://www.apachefriends.org/" -ForegroundColor Gray
    Write-Host "   - Laragon: https://laragon.org/" -ForegroundColor Gray
    Write-Host ""
    Write-Host "2. Generate after deployment (Render/Railway):" -ForegroundColor White
    Write-Host "   - Deploy first without APP_KEY" -ForegroundColor Gray
    Write-Host "   - Use Shell to run: php artisan key:generate --show" -ForegroundColor Gray
    Write-Host ""
    Write-Host "3. Use online generator:" -ForegroundColor White
    Write-Host "   - Generate random string (32 chars)" -ForegroundColor Gray
    Write-Host "   - Format as: APP_KEY=base64:YOUR_STRING" -ForegroundColor Gray
    Write-Host ""
    Write-Host "📖 See HOW-TO-GET-APP-KEY.md for detailed instructions" -ForegroundColor Cyan
}
