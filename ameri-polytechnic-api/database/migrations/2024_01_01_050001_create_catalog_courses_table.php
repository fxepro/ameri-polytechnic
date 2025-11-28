<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO academics');
        
        Schema::create('catalog_courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique(); // e.g., CS101, ENG201
            $table->string('title');
            $table->text('description');
            $table->integer('credits');
            $table->enum('level', ['intro', 'intermediate', 'advanced', 'graduate'])->default('intro');
            $table->text('prerequisites')->nullable(); // JSON or text
            $table->text('learning_outcomes')->nullable(); // JSON array
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('catalog_courses', function (Blueprint $table) {
            $table->index('course_code');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('catalog_courses');
        DB::statement('SET search_path TO public');
    }
};

