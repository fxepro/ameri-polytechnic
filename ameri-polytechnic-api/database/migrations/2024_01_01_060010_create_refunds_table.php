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
        
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('finance.payments')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->foreignId('processed_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamp('processed_at');
            $table->enum('status', ['pending', 'approved', 'processed', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
        
        Schema::table('refunds', function (Blueprint $table) {
            $table->index('payment_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('refunds');
        DB::statement('SET search_path TO public');
    }
};

