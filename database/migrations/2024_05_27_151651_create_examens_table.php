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
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullabe();
            $table->string('icon')->default('defaut_image.jpg');
            $table->longText('description')->nullable();
            $table->double('price')->nullable();
            $table->boolean('isdelete')->default(false);
            $table->boolean('isactive')->default(true);
            $table->unsignedBigInteger('laboratorie_id')->nullable();
            $table->foreign('laboratorie_id')->references('id')->on('laboratories')->onDelete('restrict')->onUpdate('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
