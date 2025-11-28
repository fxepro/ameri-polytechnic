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
        DB::statement('SET search_path TO instructors');
        
        Schema::create('content_libraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('category', ['lecture', 'assignment', 'resource', 'template', 'other'])->default('resource');
            $table->boolean('is_shared')->default(false); // Shared with other instructors
            $table->timestamps();
        });
        
        Schema::table('content_libraries', function (Blueprint $table) {
            $table->index('instructor_id');
            $table->index('document_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('content_libraries');
        DB::statement('SET search_path TO public');
    }
};

