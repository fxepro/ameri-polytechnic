# PowerShell script to set up a completely fresh database
# Usage: .\database\scripts\setup_fresh_database.ps1 [database_name]

param(
    [string]$DatabaseName = "ameri_polytechnic_v2"
)

Write-Host "Setting up fresh database: $DatabaseName" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan

# Get database credentials from .env
$envFile = Join-Path $PSScriptRoot "..\..\.env"
if (Test-Path $envFile) {
    $envContent = Get-Content $envFile
    $dbHost = ($envContent | Select-String "DB_HOST=").ToString().Split("=")[1].Trim()
    $dbPort = ($envContent | Select-String "DB_PORT=").ToString().Split("=")[1].Trim()
    $dbUser = ($envContent | Select-String "DB_USERNAME=").ToString().Split("=")[1].Trim()
    $dbPass = ($envContent | Select-String "DB_PASSWORD=").ToString().Split("=")[1].Trim()
} else {
    $dbHost = "127.0.0.1"
    $dbPort = "5432"
    $dbUser = "postgres"
    $dbPass = ""
}

Write-Host "`n1. Dropping existing database (if exists)..." -ForegroundColor Yellow
$dropQuery = "DROP DATABASE IF EXISTS $DatabaseName;"
psql -h $dbHost -p $dbPort -U $dbUser -d postgres -c $dropQuery

Write-Host "2. Creating new database..." -ForegroundColor Yellow
$createQuery = "CREATE DATABASE $DatabaseName;"
psql -h $dbHost -p $dbPort -U $dbUser -d postgres -c $createQuery

Write-Host "`n✅ Database '$DatabaseName' created successfully!" -ForegroundColor Green
Write-Host "`nNext steps:" -ForegroundColor Cyan
Write-Host "1. Update .env file: DB_DATABASE=$DatabaseName" -ForegroundColor White
Write-Host "2. Run: php artisan migrate" -ForegroundColor White


