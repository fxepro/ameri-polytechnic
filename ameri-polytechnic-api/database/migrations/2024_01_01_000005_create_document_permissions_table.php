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
        
        Schema::create('document_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->foreignId('auth_user_id')->nullable()->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('role_id')->nullable()->constrained('admin.roles')->onDelete('cascade');
            $table->enum('permission', ['view', 'edit', 'delete', 'download', 'share']);
            $table->foreignId('granted_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamp('granted_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('document_permissions', function (Blueprint $table) {
            $table->index('document_id');
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
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('document_permissions');
        DB::statement('SET search_path TO public');
    }
};

