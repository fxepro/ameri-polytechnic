<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO finance');
        
        Schema::create('grant_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grant_id')->constrained('finance.grants')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->decimal('award_amount', 10, 2);
            $table->timestamp('awarded_at');
            $table->enum('status', ['awarded', 'disbursed', 'revoked', 'expired'])->default('awarded');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('grant_awards', function (Blueprint $table) {
            $table->index('grant_id');
            $table->index('user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('grant_awards');
        DB::statement('SET search_path TO public');
    }
};

