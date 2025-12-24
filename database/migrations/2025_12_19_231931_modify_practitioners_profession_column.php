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
        // Modifier la colonne profession de enum à string (compatible SQLite et autres via doctrine/dbal)
        Schema::table('practitioners', function (Blueprint $table) {
            $table->string('profession', 100)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Impossible de remettre en enum de façon portable avec SQLite, on laisse en string
    }
};
