<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move billing_invoices table from admin to finance schema
        DB::statement('ALTER TABLE admin.billing_invoices SET SCHEMA finance');
        DB::statement('ALTER TABLE finance.billing_invoices RENAME TO invoices');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE finance.invoices RENAME TO billing_invoices');
        DB::statement('ALTER TABLE finance.billing_invoices SET SCHEMA admin');
    }
};

