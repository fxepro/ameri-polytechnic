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
        DB::statement('SET search_path TO admissions');
        
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('admissions.applicants')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('admin.programs')->onDelete('cascade');
            $table->enum('status', ['submitted', 'in_review', 'accepted', 'rejected', 'waitlisted'])->default('submitted');
            $table->timestamp('submitted_at');
            $table->foreignId('reviewed_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->json('essay_responses')->nullable(); // Essay answers and responses
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('applications', function (Blueprint $table) {
            $table->index('applicant_id');
            $table->index('program_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('applications');
        DB::statement('SET search_path TO public');
    }
};

