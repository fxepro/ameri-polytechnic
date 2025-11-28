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
        
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lms.module_lessons')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->float('progress_percent')->default(0);
            $table->integer('time_spent_minutes')->default(0);
            $table->timestamp('last_accessed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('lesson_progress', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('lesson_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('lesson_progress');
        DB::statement('SET search_path TO public');
    }
};

