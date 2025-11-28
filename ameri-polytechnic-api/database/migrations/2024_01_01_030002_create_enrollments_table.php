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
        
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('admin.programs')->onDelete('cascade');
            $table->date('start_date');
            $table->date('expected_graduation');
            $table->enum('status', ['active', 'completed', 'dropped', 'paused'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('enrollments', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('program_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('enrollments');
        DB::statement('SET search_path TO public');
    }
};

