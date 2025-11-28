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
        
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->string('grant_name');
            $table->enum('grant_type', ['federal', 'state', 'institutional', 'private'])->default('federal');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('amount_type', ['fixed', 'variable'])->default('fixed');
            $table->text('eligibility_criteria');
            $table->date('application_deadline')->nullable();
            $table->enum('status', ['active', 'closed', 'expired'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('grants', function (Blueprint $table) {
            $table->index('grant_type');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('grants');
        DB::statement('SET search_path TO public');
    }
};

