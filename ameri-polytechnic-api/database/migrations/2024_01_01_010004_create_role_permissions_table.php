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
        
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('admin.roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('admin.permissions')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->unique(['role_id', 'permission_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('role_permissions');
        DB::statement('SET search_path TO public');
    }
};

