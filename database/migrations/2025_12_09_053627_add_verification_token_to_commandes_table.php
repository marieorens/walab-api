<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->string('verification_token')->unique()->nullable()->after('statut');
            $table->timestamp('token_expires_at')->nullable()->after('verification_token');
            $table->boolean('is_verified')->default(false)->after('token_expires_at');
            $table->timestamp('verified_at')->nullable()->after('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commandes', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'token_expires_at', 'is_verified', 'verified_at']);
        });
    }
};
