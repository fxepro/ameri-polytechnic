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
        
        Schema::create('section_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('academics.course_sections')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->enum('status', ['enrolled', 'dropped', 'withdrawn', 'completed', 'incomplete'])->default('enrolled');
            $table->date('drop_date')->nullable();
            $table->text('drop_reason')->nullable();
            $table->timestamps();
        });
        
        Schema::table('section_students', function (Blueprint $table) {
            $table->index('section_id');
            $table->index('student_id');
            $table->index('status');
            $table->unique(['section_id', 'student_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('section_students');
        DB::statement('SET search_path TO public');
    }
};

