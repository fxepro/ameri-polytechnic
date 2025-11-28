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
        
        Schema::create('tutoring_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('subject');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('tutoring_sessions', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('tutor_id');
            $table->index('scheduled_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('tutoring_sessions');
        DB::statement('SET search_path TO public');
    }
};

