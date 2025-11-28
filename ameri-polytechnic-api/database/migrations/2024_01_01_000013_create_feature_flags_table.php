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
        DB::statement('SET search_path TO shared');
        
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('flag_name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->json('target_users')->nullable(); // Specific user IDs
            $table->json('target_roles')->nullable(); // Specific role IDs
            $table->integer('rollout_percentage')->default(0); // 0-100
            $table->timestamps();
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('feature_flags');
        DB::statement('SET search_path TO public');
    }
};

