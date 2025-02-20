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
            // Création de la colonne 'id' comme clé primaire auto-incrémentée
            $table->bigIncrements('id');

            // Ajout des autres colonnes
            $table->string('sujet');
            $table->string('nom');
            $table->string('telephone');
            $table->string('email');
            $table->text('contenu');
            
            // Ajout de la clé étrangère 'annonce_id' qui référence la table 'annonces'
            $table->unsignedBigInteger('annonce_id');
            $table->foreign('annonce_id')->references('id')->on('annoncee')->onDelete('cascade');

            // Ajout de timestamps si nécessaire
            $table->timestamps();
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
