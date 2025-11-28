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
        
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('type', ['email', 'sms', 'push', 'in_app']);
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed', 'delivered'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
        
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('notifications');
        DB::statement('SET search_path TO public');
    }
};

