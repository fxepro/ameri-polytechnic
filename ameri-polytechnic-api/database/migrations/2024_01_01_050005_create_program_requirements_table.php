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
        
        Schema::create('program_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('academics.programs')->onDelete('cascade');
            $table->enum('requirement_type', ['course', 'credit_hours', 'gpa', 'certification', 'experience', 'other']);
            $table->text('requirement_text');
            $table->boolean('is_mandatory')->default(true);
            $table->integer('order_index')->nullable();
            $table->timestamps();
        });
        
        Schema::table('program_requirements', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('requirement_type');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        Schema::dropIfExists('program_requirements');
        DB::statement('SET search_path TO public');
    }
};

