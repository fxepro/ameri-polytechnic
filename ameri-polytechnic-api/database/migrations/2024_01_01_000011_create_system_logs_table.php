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
        
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['debug', 'info', 'warning', 'error', 'critical'])->default('info');
            $table->text('message');
            $table->json('context')->nullable();
            $table->foreignId('auth_user_id')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');
        });
        
        Schema::table('system_logs', function (Blueprint $table) {
            $table->index('level');
            $table->index('auth_user_id');
            $table->index('created_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('system_logs');
        DB::statement('SET search_path TO public');
    }
};

