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
        
        Schema::create('module_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('lms.course_modules')->onDelete('cascade');
            $table->enum('lesson_type', ['video', 'pdf', 'text', 'slide', 'link', 'interactive', 'assignment', 'quiz']);
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('video_url')->nullable();
            $table->integer('order_index');
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        
        Schema::table('module_lessons', function (Blueprint $table) {
            $table->index('module_id');
            $table->index('lesson_type');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('module_lessons');
        DB::statement('SET search_path TO public');
    }
};

