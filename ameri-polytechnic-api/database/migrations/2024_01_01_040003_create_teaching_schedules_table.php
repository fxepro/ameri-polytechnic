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
        
        Schema::create('teaching_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
        
        Schema::table('teaching_schedules', function (Blueprint $table) {
            $table->index('instructor_id');
            $table->index('course_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('teaching_schedules');
        DB::statement('SET search_path TO public');
    }
};

