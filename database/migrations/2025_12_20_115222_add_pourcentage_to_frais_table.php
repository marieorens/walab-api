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
        Schema::table('frais', function (Blueprint $table) {
            $table->integer('pourcentage_majoration')->default(40)->after('frais');
            // Par défaut 40%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frais', function (Blueprint $table) {
            $table->dropColumn('pourcentage_majoration');
        });
    }
};
