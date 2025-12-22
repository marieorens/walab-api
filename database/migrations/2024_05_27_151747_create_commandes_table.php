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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullabe();
            $table->string('type')->nullable();
            $table->string('adress')->nullable();
            $table->string('statut')->nullable();
            $table->boolean('isdelete')->default(false);
            $table->unsignedBigInteger('examen_id')->nullable();
            $table->unsignedBigInteger('type_bilan_id')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();

            // $table->foreignId('examen_id')->constrained('examens')->onDelete('restrict');
            // $table->foreignId('type_bilan_id')->constrained('type_bilans')->onDelete('restrict');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            // $table->foreignId('agent_id')->constrained('users')->onDelete('restrict');
            $table->foreign('examen_id')->references('id')->on('examens')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('type_bilan_id')->references('id')->on('type_bilans')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
