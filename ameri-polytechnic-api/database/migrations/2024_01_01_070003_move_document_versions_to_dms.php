<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Move document_versions table from shared to dms schema
        DB::statement('ALTER TABLE shared.document_versions SET SCHEMA dms');
        
        // Update foreign key constraint to reference dms.documents
        DB::statement('ALTER TABLE dms.document_versions DROP CONSTRAINT IF EXISTS document_versions_document_id_foreign');
        DB::statement('ALTER TABLE dms.document_versions ADD CONSTRAINT document_versions_document_id_foreign FOREIGN KEY (document_id) REFERENCES dms.documents(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE dms.document_versions SET SCHEMA shared');
    }
};

