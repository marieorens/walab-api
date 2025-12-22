<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('paiement_id')->nullable()->constrained('paiements')->onDelete('set null');
            $table->unsignedBigInteger('commande_id')->nullable();
            $table->enum('type', ['credit', 'debit', 'withdrawal', 'adjustment'])->default('credit');
            $table->decimal('montant', 15, 2);
            $table->decimal('montant_avant', 15, 2);
            $table->decimal('montant_apres', 15, 2);
            $table->string('description')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->timestamps();

            $table->index(['wallet_id', 'created_at']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
