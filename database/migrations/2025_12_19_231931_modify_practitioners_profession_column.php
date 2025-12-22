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
        // Modifier la colonne profession de enum à string
        DB::statement("ALTER TABLE practitioners MODIFY profession VARCHAR(100) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre en enum (attention: les professions personnalisées seront perdues)
        DB::statement("ALTER TABLE practitioners MODIFY profession ENUM(
            'general_practitioner',
            'specialist_doctor',
            'midwife',
            'nurse',
            'nursing_assistant',
            'physiotherapist',
            'psychologist',
            'nutritionist'
        ) NOT NULL");
    }
};
