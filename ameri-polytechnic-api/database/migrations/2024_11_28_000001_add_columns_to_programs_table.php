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
        DB::statement('SET search_path TO academics');
        
        Schema::table('programs', function (Blueprint $table) {
            $table->text('overview')->nullable()->after('description');
            $table->string('program_code')->nullable()->after('name');
            $table->jsonb('skills')->nullable();
            $table->jsonb('career_paths')->nullable();
            $table->jsonb('certifications')->nullable();
            $table->string('program_length')->nullable()->after('duration_months');
            $table->string('delivery_mode')->nullable()->after('format');
            $table->decimal('tuition', 10, 2)->nullable()->after('tuition_cost');
            $table->string('salary_range')->nullable();
            $table->text('industry_outlook')->nullable();
        });
        
        Schema::table('programs', function (Blueprint $table) {
            $table->index('program_code');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO academics');
        
        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex(['program_code']);
            $table->dropColumn([
                'overview',
                'program_code',
                'skills',
                'career_paths',
                'certifications',
                'program_length',
                'delivery_mode',
                'tuition',
                'salary_range',
                'industry_outlook'
            ]);
        });
        
        DB::statement('SET search_path TO public');
    }
};

