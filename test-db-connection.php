<?php

// Quick database connection test script
// Run this in Render shell: php test-db-connection.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Database Connection Test ===\n\n";

// Check environment variables
echo "Environment Variables:\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION', 'not set') . "\n";
echo "DB_HOST: " . env('DB_HOST', 'not set') . "\n";
echo "DB_PORT: " . env('DB_PORT', 'not set') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE', 'not set') . "\n";
echo "DB_USERNAME: " . env('DB_USERNAME', 'not set') . "\n";
echo "DB_PASSWORD: " . (env('DB_PASSWORD') ? '***set***' : 'not set') . "\n";
echo "DB_SSLMODE: " . env('DB_SSLMODE', 'not set') . "\n";
echo "DATABASE_URL: " . (env('DATABASE_URL') ? '***set***' : 'not set') . "\n\n";

// Check database config
echo "Database Config:\n";
$config = config('database.connections.pgsql');
foreach ($config as $key => $value) {
    if ($key === 'password') {
        echo "  $key: " . ($value ? '***set***' : 'not set') . "\n";
    } else {
        echo "  $key: " . (is_array($value) ? json_encode($value) : $value) . "\n";
    }
}
echo "\n";

// Check PHP extensions
echo "PHP Extensions:\n";
echo "  pdo_pgsql: " . (extension_loaded('pdo_pgsql') ? '✓ loaded' : '✗ NOT loaded') . "\n";
echo "  pgsql: " . (extension_loaded('pgsql') ? '✓ loaded' : '✗ NOT loaded') . "\n";
echo "\n";

// Try connection
echo "Testing Connection...\n";
try {
    $pdo = DB::connection()->getPdo();
    echo "✓ SUCCESS! Connected to database.\n";
    echo "  Database: " . DB::connection()->getDatabaseName() . "\n";
    
    // Try a simple query
    $result = DB::select('SELECT version() as version');
    echo "  PostgreSQL Version: " . $result[0]->version . "\n";
    
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "  Error Code: " . $e->getCode() . "\n";
    
    if ($e instanceof \PDOException) {
        echo "  PDO Error Code: " . $e->errorInfo[0] . "\n";
        echo "  SQL State: " . ($e->errorInfo[1] ?? 'N/A') . "\n";
    }
}

echo "\n=== Test Complete ===\n";
