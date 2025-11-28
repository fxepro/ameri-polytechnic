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
        
        // Check if table already exists before creating
        if (!Schema::hasTable('auth_credentials')) {
            Schema::create('auth_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('credential_type', ['password', 'oauth_google', 'oauth_github', 'api_token'])->default('password');
            $table->string('credential_hash')->nullable(); // For passwords
            $table->string('provider_id')->nullable(); // For OAuth providers
            $table->string('api_token')->nullable()->unique(); // For API tokens
            $table->timestamp('token_expires_at')->nullable();
                $table->timestamps();
            });
            
            Schema::table('auth_credentials', function (Blueprint $table) {
                $table->index('auth_user_id');
                $table->index('api_token');
            });
        }
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('auth_credentials');
        DB::statement('SET search_path TO public');
    }
};

