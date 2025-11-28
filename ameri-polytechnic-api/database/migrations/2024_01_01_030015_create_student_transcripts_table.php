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
        DB::statement('SET search_path TO students');
        
        Schema::create('student_transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->string('grade')->nullable(); // A, B, C, etc.
            $table->decimal('gpa_points', 3, 2)->nullable();
            $table->integer('credits_earned');
            $table->string('term')->nullable(); // Fall 2024, Spring 2025, etc.
            $table->date('completion_date')->nullable();
            $table->timestamps();
        });
        
        Schema::table('student_transcripts', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('course_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('student_transcripts');
        DB::statement('SET search_path TO public');
    }
};

