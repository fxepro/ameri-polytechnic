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
        
        Schema::create('billing_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('billing_invoices', function (Blueprint $table) {
            $table->index('auth_user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admin');
        Schema::dropIfExists('billing_invoices');
        DB::statement('SET search_path TO public');
    }
};

