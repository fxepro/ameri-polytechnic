<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create all schemas
        DB::statement('CREATE SCHEMA IF NOT EXISTS shared');
        DB::statement('CREATE SCHEMA IF NOT EXISTS admissions');
        DB::statement('CREATE SCHEMA IF NOT EXISTS students');
        DB::statement('CREATE SCHEMA IF NOT EXISTS instructors');
        DB::statement('CREATE SCHEMA IF NOT EXISTS admin');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop schemas (cascade will drop all tables)
        DB::statement('DROP SCHEMA IF EXISTS shared CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS admissions CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS students CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS instructors CASCADE');
        DB::statement('DROP SCHEMA IF EXISTS admin CASCADE');
    }
};
