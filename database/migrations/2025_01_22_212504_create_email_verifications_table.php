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
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // L'email à vérifier
            $table->string('token'); // Le token de vérification
            $table->timestamp('created_at')->nullable(); // La date de création
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};
