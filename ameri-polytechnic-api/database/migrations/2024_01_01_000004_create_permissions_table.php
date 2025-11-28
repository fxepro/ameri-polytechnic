<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET search_path TO admin');
        
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('permission_name')->unique(); // e.g., course:create, document:download
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('permissions');
        DB::statement('SET search_path TO public');
    }
};

