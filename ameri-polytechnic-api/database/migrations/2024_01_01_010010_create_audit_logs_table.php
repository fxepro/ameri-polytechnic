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
        
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->string('action'); // e.g., user.created, document.deleted
            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');
        });
        
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index(['resource_type', 'resource_id']);
            $table->index('created_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('audit_logs');
        DB::statement('SET search_path TO public');
    }
};

