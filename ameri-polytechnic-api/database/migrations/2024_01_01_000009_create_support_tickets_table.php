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
        
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('category', ['tech', 'academic', 'finance', 'admissions', 'other'])->default('other');
            $table->enum('status', ['open', 'pending', 'closed', 'resolved'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('subject');
            $table->text('description');
            $table->foreignId('assigned_to')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('status');
            $table->index('category');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('support_tickets');
        DB::statement('SET search_path TO public');
    }
};

