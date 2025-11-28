<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update foreign key references after moving documents, document_permissions, document_versions
     */
    public function up(): void
    {
        // Update e_signatures to reference dms.documents
        DB::statement('ALTER TABLE shared.e_signatures DROP CONSTRAINT IF EXISTS e_signatures_document_id_foreign');
        DB::statement('ALTER TABLE shared.e_signatures ADD CONSTRAINT e_signatures_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
        
        // Update upload_logs to reference dms.documents
        DB::statement('ALTER TABLE shared.upload_logs DROP CONSTRAINT IF EXISTS upload_logs_document_id_foreign');
        DB::statement('ALTER TABLE shared.upload_logs ADD CONSTRAINT upload_logs_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE SET NULL');
        
        // Update application_documents to reference dms.documents
        DB::statement('ALTER TABLE admissions.application_documents DROP CONSTRAINT IF EXISTS application_documents_document_id_foreign');
        DB::statement('ALTER TABLE admissions.application_documents ADD CONSTRAINT application_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
        
        // Update instructor_documents to reference dms.documents (if it exists)
        try {
            DB::statement('ALTER TABLE instructors.instructor_documents DROP CONSTRAINT IF EXISTS instructor_documents_document_id_foreign');
            DB::statement('ALTER TABLE instructors.instructor_documents ADD CONSTRAINT instructor_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Table might not exist yet, skip
        }
        
        // Update content_libraries to reference dms.documents (if it exists)
        try {
            DB::statement('ALTER TABLE instructors.content_libraries DROP CONSTRAINT IF EXISTS content_libraries_document_id_foreign');
            DB::statement('ALTER TABLE instructors.content_libraries ADD CONSTRAINT content_libraries_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
        } catch (\Exception $e) {
            // Table might not exist yet, skip
        }
        
        // Update aid_documents to reference dms.documents
        DB::statement('ALTER TABLE finance.aid_documents DROP CONSTRAINT IF EXISTS aid_documents_document_id_foreign');
        DB::statement('ALTER TABLE finance.aid_documents ADD CONSTRAINT aid_documents_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        // Revert if needed
    }
};

