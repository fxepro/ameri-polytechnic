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
        
        Schema::create('module_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('module_id')->constrained('lms.course_modules')->onDelete('cascade');
            $table->float('percent_complete')->default(0);
            $table->integer('lessons_completed')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('module_progress', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('module_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('module_progress');
        DB::statement('SET search_path TO public');
    }
};

