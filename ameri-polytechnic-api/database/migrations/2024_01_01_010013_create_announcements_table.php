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
        
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('target_audience', ['all', 'students', 'instructors', 'admins', 'applicants'])->default('all');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::table('announcements', function (Blueprint $table) {
            $table->index('is_published');
            $table->index('target_audience');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('announcements');
        DB::statement('SET search_path TO public');
    }
};

