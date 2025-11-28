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
        DB::statement('SET search_path TO students');
        
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->unique()->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('student_id')->unique(); // e.g., "STU-2024-001"
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('US');
            $table->json('emergency_contact')->nullable();
            $table->timestamps();
        });
        
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('student_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('student_profiles');
        DB::statement('SET search_path TO public');
    }
};

