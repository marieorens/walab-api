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
        // En MySQL, changer un ENUM nécessite soit un ALTER TABLE brut soit une modification de colonne
        DB::statement("ALTER TABLE wallets MODIFY COLUMN type ENUM('laboratoire', 'plateforme', 'agent') NOT NULL DEFAULT 'laboratoire'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE wallets MODIFY COLUMN type ENUM('laboratoire', 'plateforme') NOT NULL DEFAULT 'laboratoire'");
    }
};
