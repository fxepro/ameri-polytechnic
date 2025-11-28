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
        
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed_amount'])->default('percentage');
            $table->decimal('amount', 10, 2);
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->enum('status', ['active', 'expired', 'inactive'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('discounts', function (Blueprint $table) {
            $table->index('discount_code');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('discounts');
        DB::statement('SET search_path TO public');
    }
};

