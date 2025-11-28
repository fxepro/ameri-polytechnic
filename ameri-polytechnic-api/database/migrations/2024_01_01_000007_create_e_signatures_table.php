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
        
        Schema::create('e_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('shared.documents')->onDelete('cascade');
            $table->foreignId('auth_user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->string('signature_hash');
            $table->string('signature_image')->nullable();
            $table->timestamp('signed_at');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
        
        Schema::table('e_signatures', function (Blueprint $table) {
            $table->index('document_id');
            $table->index('auth_user_id');
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        Schema::dropIfExists('e_signatures');
        DB::statement('SET search_path TO public');
    }
};

