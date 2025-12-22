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
        Schema::create('newletters', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullabe();
            $table->string('subject')->nullable();
            $table->string('html_file')->nullable();
            $table->string('type')->nullable();
            $table->boolean('isdelete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newletters');
    }
};
