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
        
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->string('account_code');
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->text('description');
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained('finance.transactions')->onDelete('set null');
            $table->timestamps();
        });
        
        Schema::table('ledger_entries', function (Blueprint $table) {
            $table->index('account_code');
            $table->index('transaction_date');
            $table->index('transaction_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('ledger_entries');
        DB::statement('SET search_path TO public');
    }
};

