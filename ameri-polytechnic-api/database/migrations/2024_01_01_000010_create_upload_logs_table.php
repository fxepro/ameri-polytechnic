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
        
        Schema::create('upload_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('document_id')->nullable()->constrained('shared.documents')->onDelete('set null');
            $table->enum('status', ['success', 'failed', 'pending'])->default('pending');
            $table->text('error_message')->nullable();
            $table->string('file_name');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
        
        Schema::table('upload_logs', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('upload_logs');
        DB::statement('SET search_path TO public');
    }
};

