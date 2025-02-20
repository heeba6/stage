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
        Schema::table('annoncee', function (Blueprint $table) {
            $table->json('espaceExterieur')->nullable();
            $table->json('parking')->nullable();
            $table->json('chauffage')->nullable();
            $table->json('proximite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annoncee', function (Blueprint $table) {
            $table->dropColumn(['espaceExterieur', 'parking', 'chauffage', 'proximite']);
        });
    }
};
