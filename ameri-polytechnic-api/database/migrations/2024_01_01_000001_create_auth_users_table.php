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
        // Set schema context
        DB::statement('SET search_path TO shared');
        
        // Check if table already exists before creating
        if (!Schema::hasTable('auth_users')) {
            Schema::create('auth_users', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
                $table->timestamps();
            });
            
            // Add index
            Schema::table('auth_users', function (Blueprint $table) {
                $table->index('email');
            });
        }
        
        // Reset to default schema
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('auth_users');
        DB::statement('SET search_path TO public');
    }
};
