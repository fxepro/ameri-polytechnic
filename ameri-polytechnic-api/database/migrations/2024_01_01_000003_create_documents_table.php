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
        
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('title');
            $table->enum('category', ['admissions', 'academic', 'financial', 'identity', 'course_material', 'user_upload', 'legal', 'other'])->default('other');
            $table->string('file_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size'); // File size in bytes
            $table->timestamp('uploaded_at');
            $table->enum('visibility', ['private', 'shared', 'public', 'restricted'])->default('private');
            $table->enum('status', ['active', 'archived', 'deleted'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('documents', function (Blueprint $table) {
            $table->index('owner_id');
            $table->index('category');
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
        Schema::dropIfExists('documents');
        DB::statement('SET search_path TO public');
    }
};

