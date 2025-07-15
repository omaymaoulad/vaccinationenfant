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
        Schema::create('secteurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('zone', ['urbain', 'rural']);
            $table->enum('niveau', ['1', '2']);
            // direction régionale
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secteurs');
    }
};
