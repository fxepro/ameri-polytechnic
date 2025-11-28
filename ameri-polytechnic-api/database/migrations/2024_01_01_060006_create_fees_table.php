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
        
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->enum('fee_type', ['application', 'registration', 'technology', 'lab', 'library', 'parking', 'graduation', 'other']);
            $table->string('fee_name');
            $table->decimal('amount', 10, 2);
            $table->boolean('is_percentage')->default(false);
            $table->string('applicable_to')->nullable(); // program_id, course_id, or 'all'
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
        
        Schema::table('fees', function (Blueprint $table) {
            $table->index('fee_type');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('fees');
        DB::statement('SET search_path TO public');
    }
};

