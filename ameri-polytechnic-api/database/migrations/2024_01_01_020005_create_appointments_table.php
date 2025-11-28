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
        
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('admissions.applicants')->onDelete('cascade');
            $table->foreignId('scheduled_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('appointment_type', ['interview', 'counseling', 'info_session', 'campus_tour']);
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('applicant_id');
            $table->index('scheduled_at');
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
        Schema::dropIfExists('appointments');
        DB::statement('SET search_path TO public');
    }
};

