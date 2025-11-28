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
        
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('lms.assignments')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->timestamp('submitted_at');
            $table->string('file_path')->nullable();
            $table->longText('text_submission')->nullable();
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->nullable();
            $table->foreignId('graded_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->boolean('is_late')->default(false);
            $table->timestamps();
        });
        
        Schema::table('submissions', function (Blueprint $table) {
            $table->index('assignment_id');
            $table->index('student_id');
            $table->index('submitted_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('submissions');
        DB::statement('SET search_path TO public');
    }
};

