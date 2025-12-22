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
        if (!Schema::hasColumn('users', 'token_notify')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('token_notify')->nullable();
            });
        }

        if (!Schema::hasColumn('commandes', 'date_prelevement')) {
            Schema::table('commandes', function (Blueprint $table) {
                $table->string('date_prelevement')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIfExists('token_notify');
        });

        Schema::table('commandes', function (Blueprint $table) {
            $table->dropIfExists('date_prelevement');
        });
    }
};
