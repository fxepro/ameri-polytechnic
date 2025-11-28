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
        
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('lms.quiz_questions')->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->integer('order_index');
            $table->timestamps();
        });
        
        Schema::table('quiz_options', function (Blueprint $table) {
            $table->index('question_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('quiz_options');
        DB::statement('SET search_path TO public');
    }
};

