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
        
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('transaction_type', ['payment', 'refund', 'adjustment', 'scholarship']);
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->text('description');
            $table->string('reference_number')->nullable();
            $table->foreignId('processed_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::table('finance_transactions', function (Blueprint $table) {
            $table->index('transaction_type');
            $table->index('processed_by');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('finance_transactions');
        DB::statement('SET search_path TO public');
    }
};

