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
        
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('messages', function (Blueprint $table) {
            $table->index('sender_id');
            $table->index('receiver_id');
            $table->index('read_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('messages');
        DB::statement('SET search_path TO public');
    }
};

