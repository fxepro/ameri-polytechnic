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
        
        Schema::create('forums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->boolean('is_locked')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });
        
        Schema::table('forums', function (Blueprint $table) {
            $table->index('course_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('forums');
        DB::statement('SET search_path TO public');
    }
};

