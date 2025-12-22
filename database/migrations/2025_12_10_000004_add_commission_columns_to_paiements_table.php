<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->foreignId('laboratoire_id')->nullable()->after('id')->constrained('laboratories')->onDelete('set null');
            $table->decimal('montant_laboratoire', 15, 2)->nullable()->after('montant');
            $table->decimal('montant_plateforme', 15, 2)->nullable()->after('montant_laboratoire');
            $table->decimal('pourcentage_applique', 5, 2)->nullable()->after('montant_plateforme');
            $table->boolean('commission_processed')->default(false)->after('pourcentage_applique');
        });
    }

    public function down(): void
    {
        Schema::table('paiements', function (Blueprint $table) {
            $table->dropForeign(['laboratoire_id']);
            $table->dropColumn([
                'laboratoire_id',
                'montant_laboratoire',
                'montant_plateforme',
                'pourcentage_applique',
                'commission_processed'
            ]);
        });
    }
};
