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
        
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->integer('installment_count');
            $table->enum('frequency', ['monthly', 'quarterly', 'semester', 'yearly'])->default('monthly');
            $table->date('start_date');
            $table->decimal('installment_amount', 10, 2);
            $table->enum('status', ['active', 'completed', 'cancelled', 'defaulted'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('payment_plans');
        DB::statement('SET search_path TO public');
    }
};

