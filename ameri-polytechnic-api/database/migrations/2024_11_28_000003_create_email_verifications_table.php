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
        
        if (!Schema::hasTable('email_verifications')) {
            Schema::create('email_verifications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
                $table->string('token')->unique();
                $table->timestamp('expires_at');
                $table->boolean('verified')->default(false);
                $table->timestamps();
                
                $table->index('token');
                $table->index('auth_user_id');
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
        Schema::dropIfExists('email_verifications');
        DB::statement('SET search_path TO public');
    }
};

