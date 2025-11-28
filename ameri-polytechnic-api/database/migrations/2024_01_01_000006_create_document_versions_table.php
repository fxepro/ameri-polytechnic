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
        DB::statement('SET search_path TO shared');
        
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->integer('version_number');
            $table->string('file_path');
            $table->text('changes')->nullable();
            $table->foreignId('created_by')->constrained('shared.auth_users')->onDelete('cascade');
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
        
        Schema::table('document_versions', function (Blueprint $table) {
            $table->index('document_id');
            $table->index('is_current');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('document_versions');
        DB::statement('SET search_path TO public');
    }
};

