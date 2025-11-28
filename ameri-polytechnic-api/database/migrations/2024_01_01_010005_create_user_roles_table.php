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
        
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('role_id')->constrained('admin.roles')->onDelete('cascade');
            $table->string('context_type')->nullable(); // e.g., course, program
            $table->unsignedBigInteger('context_id')->nullable(); // e.g., course_id, program_id
            $table->timestamps();
        });
        
        Schema::table('user_roles', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('role_id');
            $table->index(['context_type', 'context_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('user_roles');
        DB::statement('SET search_path TO public');
    }
};

