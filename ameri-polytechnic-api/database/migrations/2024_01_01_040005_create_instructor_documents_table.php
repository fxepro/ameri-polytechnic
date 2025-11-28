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
        
        // Drop table if exists from previous failed migration
        Schema::dropIfExists('instructor_documents');
        
        Schema::create('instructor_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('instructors.instructor_profiles')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->enum('document_type', ['syllabus', 'lecture_notes', 'presentation', 'handout', 'other']);
            $table->timestamps();
        });
        
        // Add foreign key constraint using raw SQL for cross-schema reference
        DB::statement('ALTER TABLE instructors.instructor_documents DROP CONSTRAINT IF EXISTS instructor_documents_course_id_foreign');
        DB::statement('ALTER TABLE instructors.instructor_documents ADD CONSTRAINT instructor_documents_course_id_foreign FOREIGN KEY (course_id) REFERENCES lms.courses(id) ON DELETE CASCADE');
        
        Schema::table('instructor_documents', function (Blueprint $table) {
            $table->index('instructor_id');
            $table->index('document_id');
            $table->index('course_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO instructors');
        Schema::dropIfExists('instructor_documents');
        DB::statement('SET search_path TO public');
    }
};

