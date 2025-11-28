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
        
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('instructions');
            $table->dateTime('due_date');
            $table->integer('max_score');
            $table->boolean('allow_late_submission')->default(false);
            $table->float('late_penalty_percent')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
        
        Schema::table('assignments', function (Blueprint $table) {
            $table->index('course_id');
            $table->index('due_date');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('assignments');
        DB::statement('SET search_path TO public');
    }
};

