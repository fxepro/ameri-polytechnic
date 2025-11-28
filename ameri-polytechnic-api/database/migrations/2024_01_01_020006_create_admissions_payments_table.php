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
        DB::statement('SET search_path TO admissions');
        
        Schema::create('admissions_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions.applications')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['application_fee', 'deposit']);
            $table->enum('status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
        
        Schema::table('admissions_payments', function (Blueprint $table) {
            $table->index('application_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('admissions_payments');
        DB::statement('SET search_path TO public');
    }
};

