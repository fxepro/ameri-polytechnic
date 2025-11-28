<?php

/**
 * Script to create a new database for fresh installation
 * 
 * Usage: php database/scripts/create_new_database.php [database_name]
 * 
 * Default database name: ameri_polytechnic_v2
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$dbName = $argv[1] ?? 'ameri_polytechnic_v2';

echo "Creating new database: {$dbName}...\n";

try {
    // Connect to postgres database to create new database
    $config = config('database.connections.pgsql');
    
    // Connect to default postgres database
    $pdo = new PDO(
        "pgsql:host={$config['host']};port={$config['port']};dbname=postgres",
        $config['username'],
        $config['password']
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '{$dbName}'");
    if ($stmt->rowCount() > 0) {
        echo "⚠️  Database '{$dbName}' already exists.\n";
        echo "Do you want to drop it and recreate? (This will delete all data!)\n";
        echo "Run: DROP DATABASE {$dbName}; CREATE DATABASE {$dbName};\n";
        exit(1);
    }
    
    // Create database
    $pdo->exec("CREATE DATABASE {$dbName}");
    
    echo "✅ Database '{$dbName}' created successfully!\n";
    echo "\nNext steps:\n";
    echo "1. Update .env file: DB_DATABASE={$dbName}\n";
    echo "2. Run: php artisan migrate\n";
    
} catch (\Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}


