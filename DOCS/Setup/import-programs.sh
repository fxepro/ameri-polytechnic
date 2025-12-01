#!/bin/bash

# Script to import program data from CSV files into the database
# Run this on the app VM after migrations are complete

echo "=========================================="
echo "Importing Programs from CSV Files"
echo "=========================================="
echo ""

# Navigate to Laravel API directory
cd /var/www/html/ameri-polytechnic/ameri-polytechnic-api || exit 1

# Check if CSV directory exists
CSV_DIR="$(pwd)/../../../DOCS/Programs CSV"
if [ ! -d "$CSV_DIR" ]; then
    echo "❌ Error: CSV directory not found: $CSV_DIR"
    echo "   Make sure the CSV files are in: DOCS/Programs CSV/"
    exit 1
fi

echo "📁 CSV directory: $CSV_DIR"
echo ""

# Check if import script exists
IMPORT_SCRIPT="database/scripts/import_programs_csv.php"
if [ ! -f "$IMPORT_SCRIPT" ]; then
    echo "❌ Error: Import script not found: $IMPORT_SCRIPT"
    exit 1
fi

# Count CSV files
CSV_COUNT=$(find "$CSV_DIR" -name "*.csv" -not -name "*raw*" | wc -l)
echo "📊 Found $CSV_COUNT CSV file(s) to import"
echo ""

# Run the import script
echo "🚀 Starting import..."
echo ""

sudo -u apache php "$IMPORT_SCRIPT"

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Import completed successfully!"
    echo ""
    echo "Next steps:"
    echo "  1. Verify data: sudo -u apache php artisan tinker"
    echo "     Then run: DB::statement('SET search_path TO academics'); DB::table('programs')->count();"
    echo "  2. Test API: curl http://localhost/api/programs/1"
else
    echo ""
    echo "❌ Import failed. Check the error messages above."
    exit 1
fi

