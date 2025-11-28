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
        
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('meeting_url');
            $table->string('meeting_id')->nullable();
            $table->string('meeting_password')->nullable();
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->string('recording_url')->nullable();
            $table->timestamps();
        });
        
        Schema::table('live_classes', function (Blueprint $table) {
            $table->index('course_id');
            $table->index('instructor_id');
            $table->index('scheduled_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('live_classes');
        DB::statement('SET search_path TO public');
    }
};

