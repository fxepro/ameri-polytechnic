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
        
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()->constrained('finance.invoices')->onDelete('set null');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer', 'check', 'cash', 'payment_plan', 'other']);
            $table->string('transaction_id')->nullable()->unique();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('payments', function (Blueprint $table) {
            $table->index('invoice_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('paid_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('payments');
        DB::statement('SET search_path TO public');
    }
};

