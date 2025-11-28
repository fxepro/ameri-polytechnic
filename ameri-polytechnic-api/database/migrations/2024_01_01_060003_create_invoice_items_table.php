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
        
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('finance.invoices')->onDelete('cascade');
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('item_type')->nullable(); // tuition, fee, book, etc.
            $table->timestamps();
        });
        
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->index('invoice_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('invoice_items');
        DB::statement('SET search_path TO public');
    }
};

