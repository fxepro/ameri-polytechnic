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
        
        Schema::create('tuition_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('academics.programs')->onDelete('cascade');
            $table->decimal('rate_per_credit', 10, 2);
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'expired', 'future'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('tuition_rates', function (Blueprint $table) {
            $table->index('program_id');
            $table->index('effective_date');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('tuition_rates');
        DB::statement('SET search_path TO public');
    }
};

