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
        
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->foreignId('parent_account_id')->nullable()->constrained('finance.chart_of_accounts')->onDelete('set null');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::table('chart_of_accounts', function (Blueprint $table) {
            $table->index('account_code');
            $table->index('account_type');
            $table->index('parent_account_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('chart_of_accounts');
        DB::statement('SET search_path TO public');
    }
};

