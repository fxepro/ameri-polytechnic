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
        
        Schema::create('office_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable(); // Physical or virtual
            $table->foreignId('booked_by')->nullable()->constrained('shared.auth_users')->onDelete('set null'); // Student who booked
            $table->timestamp('booked_at')->nullable(); // When booked
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
        
        Schema::table('office_hours', function (Blueprint $table) {
            $table->index('instructor_id');
            $table->index('booked_by');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('office_hours');
        DB::statement('SET search_path TO public');
    }
};

