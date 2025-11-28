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
        
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->default('US');
            $table->json('education_history')->nullable(); // Education background
            $table->timestamps();
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            $table->index('auth_user_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('applicants');
        DB::statement('SET search_path TO public');
    }
};

