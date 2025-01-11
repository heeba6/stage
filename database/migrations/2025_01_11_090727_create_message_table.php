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
        Schema::create('message', function (Blueprint $table) {
            $table->id('IdMsg');
            $table->text('contenu');
            $table->timestamp('dateMsg'); 
            $table->unsignedBigInteger('expediteur_id');
            $table->unsignedBigInteger('destinataire_id');
            $table->unsignedBigInteger('annonce_id');
            $table->timestamps();
            $table->foreign('expediteur_id')->references('IdUt')->on('utilisateur')->onDelete('cascade');
            $table->foreign('destinataire_id')->references('IdUt')->on('utilisateur')->onDelete('cascade');
            $table->foreign('annonce_id')->references('IdAn')->on('annonce')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};
