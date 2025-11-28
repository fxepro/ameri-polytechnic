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
        
        Schema::create('live_class_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_class_id')->constrained('lms.live_classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('status', ['attended', 'absent', 'late', 'excused'])->default('absent');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->timestamps();
        });
        
        Schema::table('live_class_attendance', function (Blueprint $table) {
            $table->index('live_class_id');
            $table->index('user_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO lms');
        Schema::dropIfExists('live_class_attendance');
        DB::statement('SET search_path TO public');
    }
};

