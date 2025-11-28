<?php

/**
 * Script to drop all schemas in the ameri_polytechnic database
 * 
 * Usage: php database/scripts/drop_all_schemas.php
 * 
 * WARNING: This will delete ALL data in all schemas!
 */

require __DIR__ . '/../../vendor/autoload.php';

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Dropping all schemas...\n";

try {
    DB::beginTransaction();
    
    $schemas = [
        'dms',
        'finance',
        'academics',
        'admin',
        'instructors',
        'students',
        'lms',
        'admissions',
        'shared'
    ];
    
    foreach ($schemas as $schema) {
        echo "Dropping schema: {$schema}...\n";
        DB::statement("DROP SCHEMA IF EXISTS {$schema} CASCADE");
        echo "✓ Dropped {$schema}\n";
    }
    
    DB::commit();
    
    echo "\n✅ All schemas dropped successfully!\n";
    echo "You can now run: php artisan migrate\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}


