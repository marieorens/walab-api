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
        Schema::table('resultats', function (Blueprint $table) {
            // Ajouter la colonne commande_id pour lier chaque résultat à une analyse spécifique
            $table->unsignedBigInteger('commande_id')->nullable()->after('code_commande');
            
            // Créer la clé étrangère vers la table commandes
            $table->foreign('commande_id')
                  ->references('id')
                  ->on('commandes')
                  ->onDelete('cascade');
            
            // Garder code_commande pour la compatibilité et la traçabilité
            // mais commande_id devient la référence principale
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resultats', function (Blueprint $table) {
            $table->dropForeign(['commande_id']);
            $table->dropColumn('commande_id');
        });
    }
};
