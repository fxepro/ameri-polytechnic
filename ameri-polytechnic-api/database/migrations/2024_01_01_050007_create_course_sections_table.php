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
        
        Schema::create('course_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_instance_id')->constrained('academics.course_instances')->onDelete('cascade');
            $table->string('section_number'); // e.g., "001", "A", "Morning"
            $table->text('schedule')->nullable(); // JSON or text describing schedule
            $table->string('location')->nullable();
            $table->integer('capacity')->default(25);
            $table->integer('enrolled_count')->default(0);
            $table->enum('format', ['online', 'onsite', 'hybrid'])->default('hybrid');
            $table->enum('status', ['open', 'closed', 'waitlist', 'cancelled'])->default('open');
            $table->timestamps();
        });
        
        Schema::table('course_sections', function (Blueprint $table) {
            $table->index('course_instance_id');
            $table->index('section_number');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('course_sections');
        DB::statement('SET search_path TO public');
    }
};

