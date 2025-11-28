<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update all foreign key references from admin.programs to academics.programs
     * This runs after programs table is moved to academics schema
     */
    public function up(): void
    {
        // Update applications table
        DB::statement('ALTER TABLE admissions.applications DROP CONSTRAINT IF EXISTS applications_program_id_foreign');
        DB::statement('ALTER TABLE admissions.applications ADD CONSTRAINT applications_program_id_foreign FOREIGN KEY (program_id) REFERENCES academics.programs(id) ON DELETE CASCADE');
        
        // Update enrollments table
        DB::statement('ALTER TABLE students.enrollments DROP CONSTRAINT IF EXISTS enrollments_program_id_foreign');
        DB::statement('ALTER TABLE students.enrollments ADD CONSTRAINT enrollments_program_id_foreign FOREIGN KEY (program_id) REFERENCES academics.programs(id) ON DELETE CASCADE');
        
        // Update certifications table
        DB::statement('ALTER TABLE students.certifications DROP CONSTRAINT IF EXISTS certifications_program_id_foreign');
        DB::statement('ALTER TABLE students.certifications ADD CONSTRAINT certifications_program_id_foreign FOREIGN KEY (program_id) REFERENCES academics.programs(id) ON DELETE CASCADE');
        
        // Update courses table (lms schema)
        DB::statement('ALTER TABLE lms.courses DROP CONSTRAINT IF EXISTS courses_program_id_foreign');
        DB::statement('ALTER TABLE lms.courses ADD CONSTRAINT courses_program_id_foreign FOREIGN KEY (program_id) REFERENCES academics.programs(id) ON DELETE CASCADE');
        
        // Note: tuition_rates table is created later (060005) and already references academics.programs correctly
        // No update needed for tuition_rates
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to admin.programs
        DB::statement('ALTER TABLE admissions.applications DROP CONSTRAINT IF EXISTS applications_program_id_foreign');
        DB::statement('ALTER TABLE admissions.applications ADD CONSTRAINT applications_program_id_foreign FOREIGN KEY (program_id) REFERENCES admin.programs(id) ON DELETE CASCADE');
        
        DB::statement('ALTER TABLE students.enrollments DROP CONSTRAINT IF EXISTS enrollments_program_id_foreign');
        DB::statement('ALTER TABLE students.enrollments ADD CONSTRAINT enrollments_program_id_foreign FOREIGN KEY (program_id) REFERENCES admin.programs(id) ON DELETE CASCADE');
        
        DB::statement('ALTER TABLE students.certifications DROP CONSTRAINT IF EXISTS certifications_program_id_foreign');
        DB::statement('ALTER TABLE students.certifications ADD CONSTRAINT certifications_program_id_foreign FOREIGN KEY (program_id) REFERENCES admin.programs(id) ON DELETE CASCADE');
        
        DB::statement('ALTER TABLE lms.courses DROP CONSTRAINT IF EXISTS courses_program_id_foreign');
        DB::statement('ALTER TABLE lms.courses ADD CONSTRAINT courses_program_id_foreign FOREIGN KEY (program_id) REFERENCES admin.programs(id) ON DELETE CASCADE');
        
        // Note: tuition_rates table is created later (060005) and already references academics.programs correctly
        // No revert needed for tuition_rates
    }
};

