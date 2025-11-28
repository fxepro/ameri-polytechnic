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
        
        Schema::create('rate_limits', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., api:user:123 or api:ip:192.168.1.1
            $table->integer('max_attempts');
            $table->integer('decay_seconds');
            $table->integer('attempts')->default(0);
            $table->timestamp('reset_at');
            $table->timestamps();
        });
        
        Schema::table('rate_limits', function (Blueprint $table) {
            $table->index('key');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('rate_limits');
        DB::statement('SET search_path TO public');
    }
};

