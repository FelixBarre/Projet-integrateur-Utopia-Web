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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('description');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->date('date_creation');
            $table->string('chemin_du_fichier');
            $table->bigInteger('id_employe')->unsigned();
        });

        Schema::table('rapports', function (Blueprint $table) {
            $table->foreign('id_employe')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
