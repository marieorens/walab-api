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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('montant')->nullabe();
            $table->string('code_commande')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable();
            $table->boolean('isdelete')->default(false);
            // $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('paiements');
        Schema::table('paiements', function (Blueprint $table) {
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            // Supprimez les colonnes ou modifications ajoutées dans up ici
        });
    }
};
