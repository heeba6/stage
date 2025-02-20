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
        Schema::table('favoris', function (Blueprint $table) {
            // $table->renameColumn('IdFa', 'id');
            //$table->renameColumn('IdAn', 'annonce_id');
            //$table->dropForeign(['IdAn']);
            $table->foreign('annonce_id')->references('id')->on('annoncee')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favoris', function (Blueprint $table) {
            // $table->dropForeign(['IdAn']);
            //$table->renameColumn('annonce_id', 'IdAn');
            //$table->renameColumn('id', 'IdFa');
            //$table->foreign('IdAn')->references('id')->on('annoncee')->onDelete('cascade');
            
        });
    }
};
