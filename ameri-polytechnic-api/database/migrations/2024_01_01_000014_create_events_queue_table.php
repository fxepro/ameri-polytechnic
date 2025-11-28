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
        DB::statement('SET search_path TO shared');
        
        Schema::create('events_queue', function (Blueprint $table) {
            $table->id();
            $table->string('event_type');
            $table->json('event_data');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
        
        Schema::table('events_queue', function (Blueprint $table) {
            $table->index('status');
            $table->index('event_type');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('events_queue');
        DB::statement('SET search_path TO public');
    }
};

