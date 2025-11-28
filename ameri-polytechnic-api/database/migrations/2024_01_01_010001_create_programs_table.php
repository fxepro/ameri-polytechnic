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
        
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category'); // Engineering, Tech, Trades, etc.
            $table->integer('duration_months')->default(24);
            $table->enum('format', ['online', 'onsite', 'hybrid'])->default('hybrid');
            $table->decimal('tuition_cost', 10, 2)->nullable();
            $table->text('requirements')->nullable(); // Admission requirements
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('programs', function (Blueprint $table) {
            $table->index('category');
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
        Schema::dropIfExists('programs');
        DB::statement('SET search_path TO public');
    }
};

