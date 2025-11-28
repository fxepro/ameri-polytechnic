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
        
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('lms.courses')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('absent');
            $table->timestamps();
        });
        
        Schema::table('attendance', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('course_id');
            $table->index('date');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('attendance');
        DB::statement('SET search_path TO public');
    }
};

