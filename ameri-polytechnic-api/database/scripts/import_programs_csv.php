<?php

require __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$csvDir = __DIR__ . '/../../../DOCS/Programs CSV';

if (!is_dir($csvDir)) {
    die("CSV directory not found: $csvDir\n");
}

// Get all CSV files except the raw text file
$csvFiles = glob($csvDir . '/*.csv');
$csvFiles = array_filter($csvFiles, function($file) {
    return strpos(basename($file), 'raw') === false;
});

if (empty($csvFiles)) {
    die("No CSV files found in: $csvDir\n");
}

echo "Found " . count($csvFiles) . " CSV file(s) to import\n\n";

$totalRowCount = 0;
$totalSuccessCount = 0;
$totalErrorCount = 0;

DB::statement('SET search_path TO academics');

foreach ($csvFiles as $csvFile) {
    $fileName = basename($csvFile);
    echo "\n=== Processing: $fileName ===\n";
    
    $handle = fopen($csvFile, 'r');
    if ($handle === false) {
        echo "Could not open CSV file: $csvFile\n";
        continue;
    }
    
    // Read header row
    $headers = fgetcsv($handle);
    if ($headers === false) {
        echo "Could not read CSV headers from: $csvFile\n";
        fclose($handle);
        continue;
    }
    
    $fileRowCount = 0;
    $fileSuccessCount = 0;
    $fileErrorCount = 0;
    
    while (($data = fgetcsv($handle)) !== false) {
        $fileRowCount++;
        $totalRowCount++;
        
        if (count($data) !== count($headers)) {
            echo "Skipping row $fileRowCount: column count mismatch\n";
            $fileErrorCount++;
            $totalErrorCount++;
            continue;
        }
        
        // Combine headers with data
        $row = array_combine($headers, $data);
        
        // Skip empty rows or header rows
        if (empty($row['name']) || strtolower($row['name']) === 'name') {
            continue;
        }
        
        // Convert format values - handle "On-Campus", "Diploma", "Certificate", etc.
        $formatValue = $row['format'] ?? 'hybrid';
        $format = strtolower($formatValue);
        if (!in_array($format, ['online', 'onsite', 'hybrid'])) {
            // Map various format values
            if (stripos($formatValue, 'campus') !== false || stripos($formatValue, 'onsite') !== false) {
                $format = 'onsite';
            } elseif (stripos($formatValue, 'online') !== false) {
                $format = 'online';
            } else {
                $format = 'hybrid';
            }
        }
        
        // Convert status values - CSV has "Active", need lowercase
        $statusValue = $row['status'] ?? 'active';
        $status = strtolower($statusValue);
        if (!in_array($status, ['active', 'inactive', 'archived'])) {
            $status = 'active';
        }
        
        // Extract program_length from format (Diploma/Certificate)
        $programLength = null;
        if (stripos($formatValue, 'diploma') !== false) {
            $programLength = 'Diploma';
        } elseif (stripos($formatValue, 'certificate') !== false) {
            $programLength = 'Certificate';
        }
        
        // Set delivery_mode based on format
        $deliveryMode = $format;
        
        // Handle timestamps
        $createdAt = !empty($row['created_at']) ? $row['created_at'] : now();
        $updatedAt = !empty($row['updated_at']) ? $row['updated_at'] : now();
        
        // Prepare data for insert
        $insertData = [
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
            'category' => $row['category'] ?? '',
            'duration_months' => (int)($row['duration_months'] ?? 24),
            'format' => $format,
            'tuition_cost' => !empty($row['tuition_cost']) ? (float)$row['tuition_cost'] : null,
            'requirements' => $row['requirements'] ?? null,
            'status' => $status,
            'overview' => $row['overview'] ?? null,
            'program_code' => $row['program_code'] ?? null,
            'program_length' => $programLength,
            'delivery_mode' => $deliveryMode,
            'tuition' => !empty($row['tuition_cost']) ? (float)$row['tuition_cost'] : null,
            'skills' => null, // JSONB - will need to be populated separately
            'career_paths' => null, // JSONB - will need to be populated separately
            'certifications' => null, // JSONB - will need to be populated separately
            'salary_range' => null,
            'industry_outlook' => null,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
        
        // Remove id from insert if auto-increment
        unset($insertData['id']);
        
        try {
            DB::table('programs')->insert($insertData);
            $fileSuccessCount++;
            $totalSuccessCount++;
            echo "  ✓ Imported: {$row['name']}\n";
        } catch (Exception $e) {
            $fileErrorCount++;
            $totalErrorCount++;
            echo "  ✗ Error importing row $fileRowCount ({$row['name']}): " . $e->getMessage() . "\n";
        }
    }
    
    fclose($handle);
    
    echo "  File summary: $fileSuccessCount imported, $fileErrorCount errors\n";
}

DB::statement('SET search_path TO public');

echo "\n" . str_repeat("=", 50) . "\n";
echo "IMPORT COMPLETE\n";
echo str_repeat("=", 50) . "\n";
echo "Total files processed: " . count($csvFiles) . "\n";
echo "Total rows processed: $totalRowCount\n";
echo "Successfully imported: $totalSuccessCount\n";
echo "Errors: $totalErrorCount\n";

