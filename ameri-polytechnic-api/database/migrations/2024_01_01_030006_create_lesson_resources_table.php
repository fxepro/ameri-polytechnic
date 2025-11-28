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
        DB::statement('SET search_path TO lms');
        
        Schema::create('lesson_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lms.module_lessons')->onDelete('cascade');
            $table->string('title');
            $table->string('file_path');
            $table->enum('resource_type', ['pdf', 'doc', 'image', 'audio', 'tool', 'external_link']);
            $table->unsignedBigInteger('file_size')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
        });
        
        Schema::table('lesson_resources', function (Blueprint $table) {
            $table->index('lesson_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('lesson_resources');
        DB::statement('SET search_path TO public');
    }
};

