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
        Schema::create('favoris', function (Blueprint $table) {
            $table->id('IdFa');
            $table->unsignedBigInteger('IdAn');
            $table->unsignedBigInteger('IdUt');
            $table->timestamps();
            $table->foreign('IdAn')->references('IdAn')->on('annonce')->onDelete('cascade');
            $table->foreign('IdUt')->references('IdUt')->on('utilisateur')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favoris');
    }
};
