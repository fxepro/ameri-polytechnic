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
        // Create LMS schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS lms');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP SCHEMA IF EXISTS lms CASCADE');
    }
};

