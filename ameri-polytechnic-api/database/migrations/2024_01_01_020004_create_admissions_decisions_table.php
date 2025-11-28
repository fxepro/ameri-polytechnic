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
        
        Schema::create('admissions_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions.applications')->onDelete('cascade');
            $table->enum('decision', ['accepted', 'rejected', 'waitlisted', 'deferred']);
            $table->date('decision_date');
            $table->foreignId('decision_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamp('accepted_at')->nullable(); // When applicant accepted the offer
            $table->foreignId('accepted_by')->nullable()->constrained('shared.auth_users')->onDelete('set null'); // Who accepted (self/admin)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('admissions_decisions', function (Blueprint $table) {
            $table->index('application_id');
            $table->index('decision');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('admissions_decisions');
        DB::statement('SET search_path TO public');
    }
};

