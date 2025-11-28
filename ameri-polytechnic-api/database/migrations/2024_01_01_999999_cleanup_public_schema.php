<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drop old application tables from public schema that were created before
     * the multi-schema architecture. Keep only Laravel system tables.
     */
    public function up(): void
    {
        // List of old application tables to drop from public schema
        $tablesToDrop = [
            'admissions_applications',
            'application_documents',
            'applications',
            'attendance', // Old version, we have students.attendance
            'contact_messages',
            'course_instructors',
            'courses', // Old version, we have students.courses
            'enrollment_courses',
            'enrollments', // Old version, we have students.enrollments
            'exam_results',
            'exams',
            'financial_aid',
            'interviews',
            'invoices',
            'job_postings',
            'messages', // Old version, we have shared.messages
            'partners',
            'payments', // Old version, we have students.student_payments
            'program_categories',
            'programs', // Old version, we have admin.programs
            'roles', // Old version, we have admin.roles
            'support_tickets', // Old version, we have shared.support_tickets
            'user_profiles',
            'users', // Old version, we have shared.auth_users
        ];

        // Drop tables if they exist
        foreach ($tablesToDrop as $table) {
            if (Schema::hasTable($table)) {
                DB::statement("DROP TABLE IF EXISTS public.{$table} CASCADE");
            }
        }
    }

    /**
     * Reverse the migrations.
     * 
     * Note: We don't restore these tables as they are replaced by
     * the new multi-schema architecture.
     */
    public function down(): void
    {
        // Intentionally empty - these tables are replaced by new schema structure
    }
};

