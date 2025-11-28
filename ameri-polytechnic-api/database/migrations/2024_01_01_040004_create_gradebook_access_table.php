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
        DB::statement('SET search_path TO instructors');
        
        Schema::create('gradebook_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->enum('access_level', ['full', 'read_only', 'grading_only'])->default('full');
            $table->timestamps();
        });
        
        Schema::table('gradebook_access', function (Blueprint $table) {
            $table->index('instructor_id');
            $table->index('course_id');
            $table->unique(['instructor_id', 'course_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('gradebook_access');
        DB::statement('SET search_path TO public');
    }
};

