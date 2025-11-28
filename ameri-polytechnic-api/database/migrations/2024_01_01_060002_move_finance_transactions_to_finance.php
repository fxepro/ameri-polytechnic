<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move finance_transactions table from admin to finance schema
        DB::statement('ALTER TABLE admin.finance_transactions SET SCHEMA finance');
        DB::statement('ALTER TABLE finance.finance_transactions RENAME TO transactions');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE finance.transactions RENAME TO finance_transactions');
        DB::statement('ALTER TABLE finance.finance_transactions SET SCHEMA admin');
    }
};

