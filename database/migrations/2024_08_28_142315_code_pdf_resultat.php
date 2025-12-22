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
        if (!Schema::hasColumn('resultats', 'pdf_password')) {
            Schema::table('resultats', function (Blueprint $table) {
                $table->string('pdf_password')->nullabe();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropIfExists('pdf_password');
        });
    }
};
