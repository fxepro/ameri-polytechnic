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
        
        Schema::create('section_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained('academics.course_sections')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->enum('role', ['primary', 'assistant', 'guest', 'substitute'])->default('primary');
            $table->timestamps();
        });
        
        Schema::table('section_instructors', function (Blueprint $table) {
            $table->index('section_id');
            $table->index('instructor_id');
            $table->unique(['section_id', 'instructor_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('section_instructors');
        DB::statement('SET search_path TO public');
    }
};

