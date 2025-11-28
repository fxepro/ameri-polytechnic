<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Create all 9 schemas for the new ameri_polytechnic database
     */
    public function up(): void
    {
        // Create all 9 schemas
        DB::statement('CREATE SCHEMA IF NOT EXISTS shared');
        DB::statement('CREATE SCHEMA IF NOT EXISTS admissions');
        DB::statement('CREATE SCHEMA IF NOT EXISTS lms');
        DB::statement('CREATE SCHEMA IF NOT EXISTS students');
        DB::statement('CREATE SCHEMA IF NOT EXISTS instructors');
        DB::statement('CREATE SCHEMA IF NOT EXISTS admin');
        DB::statement('CREATE SCHEMA IF NOT EXISTS academics');
        DB::statement('CREATE SCHEMA IF NOT EXISTS finance');
        DB::statement('CREATE SCHEMA IF NOT EXISTS dms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop schemas (cascade will drop all tables)
        DB::statement('DROP SCHEMA IF EXISTS dms CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS finance CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS academics CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS admin CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS instructors CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS students CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS lms CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS admissions CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS shared CASCADE');
    }
};

