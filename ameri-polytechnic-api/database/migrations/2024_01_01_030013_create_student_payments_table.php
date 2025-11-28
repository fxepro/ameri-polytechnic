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
        DB::statement('SET search_path TO students');
        
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students.student_profiles')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('admin.billing_invoices')->onDelete('set null');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['card', 'bank', 'cash', 'other']);
            $table->bigInteger('payment_plan_id')->nullable(); // Reference to payment plan
            $table->integer('installment_number')->nullable(); // Current installment
            $table->decimal('installment_amount', 12, 2)->nullable(); // Installment amount
            $table->timestamp('paid_at');
            $table->timestamps();
        });
        
        Schema::table('student_payments', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('invoice_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('student_payments');
        DB::statement('SET search_path TO public');
    }
};

