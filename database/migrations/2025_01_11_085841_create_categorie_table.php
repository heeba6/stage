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
        Schema::create('categorie', function (Blueprint $table) {
            $table->id('IdCat'); // Clé primaire avec auto-incrément
            $table->string('nomCat', 255); // Colonne pour le nom de la catégorie
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie');
    }
};
