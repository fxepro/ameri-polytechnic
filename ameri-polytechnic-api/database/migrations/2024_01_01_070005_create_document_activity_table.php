<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET search_path TO dms');
        
        Schema::create('document_activity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dms.documents')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('shared.auth_users')->onDelete('cascade');
            $table->enum('action', ['viewed', 'downloaded', 'edited', 'deleted', 'shared', 'moved', 'renamed', 'permission_changed']);
            $table->string('ip_address')->nullable();
            $table->text('details')->nullable(); // JSON or text with additional info
            $table->timestamp('created_at');
        });
        
        Schema::table('document_activity', function (Blueprint $table) {
            $table->index('document_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
        
        DB::statement('SET search_path TO public');
    }

    public function down(): void
    {
        DB::statement('SET search_path TO dms');
        Schema::dropIfExists('document_activity');
        DB::statement('SET search_path TO public');
    }
};

