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
        DB::statement('SET search_path TO instructors');
        
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->unique()->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('employee_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('department_id')->nullable()->constrained('admin.departments')->onDelete('set null');
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });
        
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('employee_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('instructor_profiles');
        DB::statement('SET search_path TO public');
    }
};

