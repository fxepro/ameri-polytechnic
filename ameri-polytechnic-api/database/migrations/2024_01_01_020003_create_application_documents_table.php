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
        DB::statement('SET search_path TO admissions');
        
        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admissions.applications')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->enum('document_type', ['id', 'transcript', 'certificate', 'passport', 'recommendation', 'other']);
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('application_documents', function (Blueprint $table) {
            $table->index('application_id');
            $table->index('document_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO admissions');
        Schema::dropIfExists('application_documents');
        DB::statement('SET search_path TO public');
    }
};

