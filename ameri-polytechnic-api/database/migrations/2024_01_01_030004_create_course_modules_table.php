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
        
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('order_index');
            $table->boolean('is_published')->default(false); // Visibility to students
            $table->timestamps();
        });
        
        Schema::table('course_modules', function (Blueprint $table) {
            $table->index('course_id');
            $table->index('is_published');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('course_modules');
        DB::statement('SET search_path TO public');
    }
};

