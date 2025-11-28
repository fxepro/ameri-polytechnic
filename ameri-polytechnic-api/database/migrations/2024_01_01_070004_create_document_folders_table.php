<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO dms');
        
        Schema::create('document_folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_folder_id')->nullable()->constrained('dms.document_folders')->onDelete('cascade');
            $table->string('name');
            $table->foreignId('owner_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('path'); // Full path like /folder1/subfolder
            $table->text('description')->nullable();
            $table->enum('visibility', ['private', 'shared', 'public'])->default('private');
            $table->timestamps();
        });
        
        Schema::table('document_folders', function (Blueprint $table) {
            $table->index('parent_folder_id');
            $table->index('owner_id');
            $table->index('path');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO dms');
        Schema::dropIfExists('document_folders');
        DB::statement('SET search_path TO public');
    }
};

