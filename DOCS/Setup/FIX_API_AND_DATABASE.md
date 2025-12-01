# Fix API Error and Import Program Data

## Current Issues

1. **API Error**: `Http failure response for https://api.ameripolytech.com/api/programs/15: 0 Unknown Error`
   - **Cause**: SSL certificates not configured (HTTPS not working)
   - **Temporary Fix**: Use HTTP instead of HTTPS until SSL is set up

2. **Empty Database**: No program data
   - **Fix**: Import programs from CSV files

## Quick Fix Steps

### Step 1: Update Frontend to Use HTTP (Temporary)

Until SSL is configured, update the Angular production environment to use HTTP:

**File**: `ameri-polytechnic/src/environments/environment.prod.ts`

```typescript
export const environment = {
  production: true,
  apiUrl: 'http://api.ameripolytech.com/api',  // Changed from https to http
  frontendUrl: 'http://ameripolytech.com'
};
```

Then rebuild and deploy:
```bash
cd ameri-polytechnic
npm run build
# Copy dist/myapp/browser/* to VM
```

### Step 2: Import Program Data

On the app VM:

```bash
# Make script executable
chmod +x /path/to/import-programs.sh

# Run import
sudo /path/to/import-programs.sh
```

Or manually:

```bash
cd /var/www/html/ameri-polytechnic/ameri-polytechnic-api
sudo -u apache php database/scripts/import_programs_csv.php
```

### Step 3: Verify Data Imported

```bash
cd /var/www/html/ameri-polytechnic/ameri-polytechnic-api
sudo -u apache php artisan tinker
```

In Tinker:
```php
DB::statement('SET search_path TO academics');
DB::table('programs')->count();
// Should show number of imported programs
DB::table('programs')->select('id', 'name')->limit(5)->get();
exit
```

### Step 4: Test API (HTTP)

```bash
# Test from VM
curl http://localhost/api/programs/1

# Test from your machine
curl http://api.ameripolytech.com/api/programs/1
```

### Step 5: Set Up SSL (Permanent Fix)

Follow the guide: `DOCS/Setup/SSL_Setup_Guide.md`

After SSL is configured:
1. Update `environment.prod.ts` back to `https://`
2. Rebuild and deploy Angular
3. Test: `curl https://api.ameripolytech.com/api/programs/1`

## Troubleshooting

### CORS Errors

The CORS middleware has been updated to allow production domains. If you still see CORS errors:

1. Clear Laravel config cache:
```bash
cd /var/www/html/ameri-polytechnic/ameri-polytechnic-api
sudo -u apache php artisan config:clear
sudo -u apache php artisan config:cache
```

2. Restart PHP-FPM:
```bash
sudo systemctl restart php-fpm
```

### Import Script Errors

If the import script fails:

1. Check CSV files exist:
```bash
ls -la /var/www/html/ameri-polytechnic/DOCS/Programs\ CSV/
```

2. Check database connection:
```bash
cd /var/www/html/ameri-polytechnic/ameri-polytechnic-api
sudo -u apache php artisan tinker
DB::connection()->getPdo();
```

3. Check schema exists:
```bash
sudo -u apache php artisan tinker
DB::statement('SET search_path TO academics');
DB::select("SELECT current_schema()");
```

### API Returns 404

Check Nginx is routing correctly:

```bash
# Check API config
sudo cat /etc/nginx/conf.d/ameri-polytechnic-api.conf

# Test Nginx config
sudo nginx -t

# Check error logs
sudo tail -f /var/log/nginx/error.log
```

