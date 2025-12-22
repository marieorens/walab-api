<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_commandes', function (Blueprint $table) {
            $table->string('type')->default('text')->after('code_commande'); // 'text' ou 'audio'
            $table->string('attachment')->nullable()->after('type'); // Chemin du fichier
            // On permet au contenu d'être vide (si c'est un vocal sans texte)
            $table->text('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('chat_commandes', function (Blueprint $table) {
            $table->dropColumn(['type', 'attachment']);

            $table->text('content')->nullable(false)->change();
        });
    }
};
