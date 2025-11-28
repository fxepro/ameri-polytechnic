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
        
        Schema::table('auth_users', function (Blueprint $table) {
            if (!Schema::hasColumn('auth_users', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('auth_users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('auth_users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
        });
        
        DB::statement('SET search_path TO public');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET search_path TO shared');
        
        Schema::table('auth_users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'phone']);
        });
        
        DB::statement('SET search_path TO public');
    }
};

