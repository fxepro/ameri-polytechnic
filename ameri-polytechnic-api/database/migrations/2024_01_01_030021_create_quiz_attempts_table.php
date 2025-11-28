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
        
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('quiz_id')->constrained('lms.quizzes')->onDelete('cascade');
            $table->integer('attempt_number');
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('score')->default(0);
            $table->integer('max_score');
            $table->float('percent_score')->default(0);
            $table->integer('time_taken_minutes')->nullable();
            $table->json('answers')->nullable(); // Student's answers
            $table->timestamps();
        });
        
        Schema::table('quiz_attempts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('quiz_id');
            $table->index('attempt_number');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('quiz_attempts');
        DB::statement('SET search_path TO public');
    }
};

