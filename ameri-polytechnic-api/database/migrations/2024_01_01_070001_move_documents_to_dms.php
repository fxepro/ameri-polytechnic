<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move documents table from shared to dms schema
        DB::statement('ALTER TABLE shared.documents SET SCHEMA dms');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE dms.documents SET SCHEMA shared');
    }
};

