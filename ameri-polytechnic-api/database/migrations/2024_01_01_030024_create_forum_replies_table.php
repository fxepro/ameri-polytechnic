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
        
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('lms.forum_threads')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->text('body');
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('forum_replies', function (Blueprint $table) {
            $table->index('thread_id');
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
        Schema::dropIfExists('forum_replies');
        DB::statement('SET search_path TO public');
    }
};

