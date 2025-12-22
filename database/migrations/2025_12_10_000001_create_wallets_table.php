<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['laboratoire', 'plateforme'])->default('laboratoire');
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('pending_balance', 15, 2)->default(0);
            $table->decimal('total_entrees', 15, 2)->default(0);
            $table->decimal('total_sorties', 15, 2)->default(0);
            $table->timestamp('last_withdrawal_at')->nullable();
            $table->enum('status', ['active', 'suspended', 'blocked'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
