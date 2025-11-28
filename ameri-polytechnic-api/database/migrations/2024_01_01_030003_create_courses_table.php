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
        
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('admin.programs')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->integer('credits');
            $table->enum('course_level', ['intro', 'intermediate', 'advanced'])->default('intro');
            $table->foreignId('approved_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->enum('approval_status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
        
        Schema::table('courses', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('approval_status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('courses');
        DB::statement('SET search_path TO public');
    }
};

