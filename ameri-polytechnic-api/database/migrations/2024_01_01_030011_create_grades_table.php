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
        
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('lms.submissions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->decimal('score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->foreignId('graded_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamp('graded_at');
            $table->timestamp('released_at')->nullable(); // When released to student
            $table->enum('dispute_status', ['none', 'pending', 'under_review', 'resolved', 'rejected'])->default('none');
            $table->text('dispute_notes')->nullable();
            $table->foreignId('disputed_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('disputed_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('grades', function (Blueprint $table) {
            $table->index('submission_id');
            $table->index('student_id');
            $table->index('dispute_status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('grades');
        DB::statement('SET search_path TO public');
    }
};

