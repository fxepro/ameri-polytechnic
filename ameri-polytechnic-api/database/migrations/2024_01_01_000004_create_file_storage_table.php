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
        
        Schema::create('file_storage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->enum('storage_type', ['s3', 'local', 'cdn'])->default('s3');
            $table->string('bucket_name')->nullable();
            $table->string('file_key');
            $table->string('cdn_url')->nullable();
            $table->timestamps();
        });
        
        Schema::table('file_storage', function (Blueprint $table) {
            $table->index('document_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('file_storage');
        DB::statement('SET search_path TO public');
    }
};

