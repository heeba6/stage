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
        Schema::create('annonce', function (Blueprint $table) {
            $table->id('IdAn'); // Clé primaire
            $table->string('titre'); // Titre de l'annonce
            $table->text('description'); // Description de l'annonce
            $table->decimal('prix', 10, 2); // Prix (décimal avec 10 chiffres dont 2 après la virgule)
            $table->string('adressse'); // Adresse de l'annonce
            $table->string('type'); // Type de l'annonce
            $table->date('datePub'); // Date de publication
            $table->boolean('etat')->default(1); // État (actif ou non)
            $table->unsignedBigInteger('IdUt'); // Clé étrangère pour l'utilisateur
            $table->string('photo')->nullable(); // Photo de l'annonce (nullable)
            $table->timestamps(); // Colonnes created_at et updated_at

            // Définir une contrainte de clé étrangère
            $table->foreign('IdUt')
                ->references('IdUt') // Supposons que la clé primaire de l'utilisateur est 'id'
                ->on('utilisateur') // Nom de la table des utilisateurs
                ->onDelete('cascade'); // Suppression en cascade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonce');
    }
};
