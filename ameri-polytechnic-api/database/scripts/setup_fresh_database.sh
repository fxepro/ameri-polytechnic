#!/bin/bash

# Script to set up a completely fresh database
# Usage: ./database/scripts/setup_fresh_database.sh [database_name]

DB_NAME=${1:-ameri_polytechnic_v2}

echo "Setting up fresh database: $DB_NAME"
echo "======================================"

# Get database credentials from .env or use defaults
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-5432}
DB_USER=${DB_USERNAME:-postgres}

echo "1. Creating database..."
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -c "DROP DATABASE IF EXISTS $DB_NAME;"
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d postgres -c "CREATE DATABASE $DB_NAME;"

echo "✅ Database created!"
echo ""
echo "2. Update your .env file:"
echo "   DB_DATABASE=$DB_NAME"
echo ""
echo "3. Run migrations:"
echo "   php artisan migrate"


