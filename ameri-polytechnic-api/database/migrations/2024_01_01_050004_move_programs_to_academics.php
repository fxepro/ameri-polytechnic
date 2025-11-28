<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move programs table from admin to academics schema
        DB::statement('ALTER TABLE admin.programs SET SCHEMA academics');
        
        // Update any indexes if needed
        DB::statement('SET search_path TO academics');
        Schema::table('programs', function (Blueprint $table) {
            // Indexes should already exist, but ensure they're correct
        });
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        // Move back to admin schema
        DB::statement('ALTER TABLE academics.programs SET SCHEMA admin');
    }
};

