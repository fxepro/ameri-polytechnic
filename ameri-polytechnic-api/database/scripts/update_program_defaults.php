<?php

/**
 * Script to update all programs with default values:
 * - certifications: ["Diploma"]
 * - program_length: "14 months"
 * - delivery_mode: "hybrid"
 * 
 * Usage: php database/scripts/update_program_defaults.php
 */

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Updating all programs with default values...\n\n";

DB::statement('SET search_path TO academics');

try {
    // Get count of programs to update
    $totalPrograms = DB::table('programs')->count();
    echo "Found $totalPrograms programs to update\n\n";
    
    if ($totalPrograms === 0) {
        echo "No programs found. Nothing to do.\n";
        DB::statement('SET search_path TO public');
        exit(0);
    }
    
    // Prepare certifications as JSONB array
    $certifications = json_encode(['Diploma']);
    
    // Update all programs
    $updated = DB::table('programs')
        ->update([
            'certifications' => $certifications,
            'program_length' => '14 months',
            'delivery_mode' => 'hybrid',
            'updated_at' => now()
        ]);
    
    echo "Successfully updated $updated programs\n\n";
    echo "Updated values:\n";
    echo "  - certifications: [\"Diploma\"]\n";
    echo "  - program_length: \"14 months\"\n";
    echo "  - delivery_mode: \"hybrid\"\n";
    
    // Verify a few records
    echo "\nVerifying updates (showing first 3 programs):\n";
    $sample = DB::table('programs')
        ->select('id', 'name', 'certifications', 'program_length', 'delivery_mode')
        ->limit(3)
        ->get();
    
    foreach ($sample as $program) {
        $certs = $program->certifications ? json_decode($program->certifications, true) : null;
        echo "\n  Program ID {$program->id}: {$program->name}\n";
        echo "    - certifications: " . ($certs ? json_encode($certs) : 'NULL') . "\n";
        echo "    - program_length: " . ($program->program_length ?? 'NULL') . "\n";
        echo "    - delivery_mode: " . ($program->delivery_mode ?? 'NULL') . "\n";
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Update complete!\n";
    
} catch (Exception $e) {
    echo "\nError: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

DB::statement('SET search_path TO public');

