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
        
        Schema::create('forum_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_id')->constrained('lms.forums')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('title');
            $table->text('body');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('reply_count')->default(0);
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->index('forum_id');
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
        Schema::dropIfExists('forum_threads');
        DB::statement('SET search_path TO public');
    }
};

