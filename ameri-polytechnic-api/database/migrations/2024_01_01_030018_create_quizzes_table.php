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
        
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->string('title');
            $table->text('instructions');
            $table->integer('time_limit')->nullable(); // Minutes
            $table->integer('attempts_allowed')->default(1);
            $table->integer('passing_score')->nullable();
            $table->boolean('show_results')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        
        Schema::table('quizzes', function (Blueprint $table) {
            $table->index('course_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('quizzes');
        DB::statement('SET search_path TO public');
    }
};

