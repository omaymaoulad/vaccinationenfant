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
        Schema::create('vaccinsstatiques', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('id_secteur');
        $table->integer('annee');
        $table->integer('semaine'); // de 1 à 52
        $table->string('nom_vaccin', 50); // exemple : Penta3, RR 9 mois
        $table->string('tranche_age', 20); // exemple : <1 an, 9 mois
        $table->integer('enfants_cibles');
        $table->integer('enfants_vaccines');

        // Champ calculé (non supporté nativement par Laravel, on le fait en SQL brut ensuite)
        $table->decimal('pourcentage_vaccination', 5, 2)->nullable();

        $table->timestamps();

        $table->foreign('id_secteur')->references('id')->on('secteurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccinsstatiques');
    }
};
