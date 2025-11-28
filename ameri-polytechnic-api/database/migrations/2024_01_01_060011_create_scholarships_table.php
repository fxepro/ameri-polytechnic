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
        
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('scholarship_name');
            $table->text('description');
            $table->decimal('amount', 10, 2);
            $table->enum('amount_type', ['fixed', 'percentage', 'full_tuition'])->default('fixed');
            $table->text('eligibility_criteria');
            $table->date('application_deadline');
            $table->integer('available_slots')->nullable();
            $table->integer('awarded_count')->default(0);
            $table->enum('status', ['active', 'closed', 'expired'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('scholarships', function (Blueprint $table) {
            $table->index('status');
            $table->index('application_deadline');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('scholarships');
        DB::statement('SET search_path TO public');
    }
};

