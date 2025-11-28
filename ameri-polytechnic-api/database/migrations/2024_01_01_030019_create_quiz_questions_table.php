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
        
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('lms.quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['mcq', 'true_false', 'short_answer', 'fill_blank']);
            $table->integer('points');
            $table->integer('order_index');
            $table->text('explanation')->nullable();
            $table->timestamps();
        });
        
        Schema::table('quiz_questions', function (Blueprint $table) {
            $table->index('quiz_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('quiz_questions');
        DB::statement('SET search_path TO public');
    }
};

