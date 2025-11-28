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
        DB::statement('SET search_path TO admin');
        
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value');
            $table->enum('setting_type', ['string', 'integer', 'boolean', 'json'])->default('string');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('system_settings');
        DB::statement('SET search_path TO public');
    }
};

