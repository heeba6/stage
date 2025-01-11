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
        Schema::create('utilisateur', function (Blueprint $table) {
            //$table->bigIncrements('IdUt'); // Crée une clé primaire auto-incrémentée avec un type `bigint`
            $table->id('IdUt');
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique(); // L'email doit être unique
            $table->string('mtP'); // Mot de passe
            $table->string('role'); // Rôle de l'utilisateur (admin, utilisateur, etc.)
            $table->timestamps(); // Champs created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utilisateur');
    }
};
