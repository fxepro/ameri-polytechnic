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
        
        Schema::create('aid_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->enum('aid_type', ['fafsa', 'tax_return', 'w2', 'bank_statement', 'other']);
            $table->timestamp('submitted_at');
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('shared.auth_users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('aid_documents', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO finance');
        Schema::dropIfExists('aid_documents');
        DB::statement('SET search_path TO public');
    }
};

