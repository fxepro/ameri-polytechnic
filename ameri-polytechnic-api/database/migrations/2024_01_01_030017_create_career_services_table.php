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
        
        Schema::create('career_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->enum('service_type', ['resume_review', 'interview_prep', 'job_search', 'career_counseling', 'networking']);
            $table->enum('status', ['requested', 'scheduled', 'completed', 'cancelled'])->default('requested');
            $table->dateTime('scheduled_at')->nullable();
            $table->foreignId('advisor_id')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('career_services', function (Blueprint $table) {
            $table->index('student_id');
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
        Schema::dropIfExists('career_services');
        DB::statement('SET search_path TO public');
    }
};

