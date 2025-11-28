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
        
        Schema::create('admissions_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->enum('event_type', ['open_house', 'info_session', 'webinar', 'campus_tour']);
            $table->text('description')->nullable();
            $table->dateTime('event_date');
            $table->string('location')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->foreignId('created_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::table('admissions_events', function (Blueprint $table) {
            $table->index('event_date');
            $table->index('event_type');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('admissions_events');
        DB::statement('SET search_path TO public');
    }
};

