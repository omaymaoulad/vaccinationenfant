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
        Schema::create('enfants_statistiques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_secteur');
            $table->integer('annee');
            $table->integer('nb_moins_1_an');
            $table->integer('nb_18_mois');
            $table->integer('nb_5_ans');
            $table->timestamps();
            $table->foreign('id_secteur')->references('id')->on('secteurs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enfants_statistiques');
    }
};
