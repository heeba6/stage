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
        Schema::create('annoncee', function (Blueprint $table) {
            $table->id(); // Clé primaire personnalisée
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 10, 2);
            $table->decimal('surface_habitable', 10, 2)->nullable();
            $table->string('adresse');
            $table->string('vocation');
            $table->string('type');
            $table->date('datePub');
            $table->string('etat');
            $table->foreignId('IdUt'); // Assuming 'users' is the table name for users
            $table->foreign('IdUt')->references('IdUt')->on('utilisateur')->onDelete('cascade');
            $table->json('photos')->nullable(); // Nouveau champ pour les photos (tableau JSON)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annoncee');
    }
};
