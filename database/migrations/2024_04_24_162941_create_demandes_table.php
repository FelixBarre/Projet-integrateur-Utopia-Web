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
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->date('date_demande');
            $table->date('date_traitement');
            $table->bigInteger('id_etat_demande')->unsigned();
            $table->bigInteger('id_demandeur')->unsigned();
        });

        Schema::table('demandes', function (Blueprint $table) {
            $table->foreign('id_etat_demande')->references('id')->on('etat_demandes');
            $table->foreign('id_demandeur')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};