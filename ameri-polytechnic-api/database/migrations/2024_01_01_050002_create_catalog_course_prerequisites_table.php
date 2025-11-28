<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO academics');
        
        Schema::create('catalog_course_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('academics.catalog_courses')->onDelete('cascade');
            $table->foreignId('prerequisite_course_id')->constrained('academics.catalog_courses')->onDelete('cascade');
            $table->boolean('is_required')->default(true);
            $table->enum('prerequisite_type', ['prerequisite', 'corequisite', 'recommended'])->default('prerequisite');
            $table->timestamps();
        });
        
        Schema::table('catalog_course_prerequisites', function (Blueprint $table) {
            $table->index('course_id');
            $table->index('prerequisite_course_id');
            $table->unique(['course_id', 'prerequisite_course_id']);
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('catalog_course_prerequisites');
        DB::statement('SET search_path TO public');
    }
};

