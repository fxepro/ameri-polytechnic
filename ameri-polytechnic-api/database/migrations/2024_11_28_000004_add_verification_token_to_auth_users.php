<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO shared');
        
        Schema::table('auth_users', function (Blueprint $table) {
            if (!Schema::hasColumn('auth_users', 'verification_token')) {
                $table->string('verification_token')->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('auth_users', 'verification_token_expires_at')) {
                $table->timestamp('verification_token_expires_at')->nullable()->after('verification_token');
            }
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        
        Schema::table('auth_users', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'verification_token_expires_at']);
        });
        
        DB::statement('SET search_path TO public');
    }
};

