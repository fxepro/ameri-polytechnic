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
        
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('method_type', ['credit_card', 'debit_card', 'bank_account', 'other']);
            $table->string('last_four')->nullable(); // Last 4 digits of card/account
            $table->string('card_brand')->nullable(); // Visa, Mastercard, etc.
            $table->date('expiry_date')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('status', ['active', 'expired', 'inactive'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('payment_methods', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('is_default');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('payment_methods');
        DB::statement('SET search_path TO public');
    }
};

