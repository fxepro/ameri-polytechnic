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
        
        Schema::create('course_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_course_id')->constrained('academics.catalog_courses')->onDelete('cascade');
            $table->string('term'); // Fall 2024, Spring 2025, etc.
            $table->integer('year');
            $table->foreignId('instructor_id')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->integer('max_enrollment')->default(30);
            $table->integer('current_enrollment')->default(0);
            $table->enum('status', ['scheduled', 'open', 'closed', 'completed', 'cancelled'])->default('scheduled');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
        
        Schema::table('course_instances', function (Blueprint $table) {
            $table->index('catalog_course_id');
            $table->index('term');
            $table->index('year');
            $table->index('instructor_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('course_instances');
        DB::statement('SET search_path TO public');
    }
};

