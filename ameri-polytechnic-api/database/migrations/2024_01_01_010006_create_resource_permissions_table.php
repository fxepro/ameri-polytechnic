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
        
        Schema::create('resource_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // e.g., document, course
            $table->unsignedBigInteger('resource_id');
            $table->foreignId('auth_user_id')->nullable()->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained('admin.roles')->onDelete('cascade');
            $table->string('permission'); // e.g., view, edit, delete
            $table->boolean('allow')->default(true); // true = allow, false = deny
            $table->timestamps();
        });
        
        Schema::table('resource_permissions', function (Blueprint $table) {
            $table->index(['resource_type', 'resource_id']);
            $table->index('auth_user_id');
            $table->index('role_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('resource_permissions');
        DB::statement('SET search_path TO public');
    }
};

