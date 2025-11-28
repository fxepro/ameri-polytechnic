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
        DB::statement('SET search_path TO students');
        
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->foreignId('program_id')->constrained('admin.programs')->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->date('issue_date');
            $table->string('digital_signature');
            $table->string('certificate_file_path')->nullable();
            $table->string('verification_url')->nullable();
            $table->boolean('is_revoked')->default(false);
            $table->timestamp('revoked_at')->nullable();
            $table->text('revoked_reason')->nullable();
            $table->timestamps();
        });
        
        Schema::table('certifications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('program_id');
            $table->index('certificate_number');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO students');
        Schema::dropIfExists('certifications');
        DB::statement('SET search_path TO public');
    }
};

