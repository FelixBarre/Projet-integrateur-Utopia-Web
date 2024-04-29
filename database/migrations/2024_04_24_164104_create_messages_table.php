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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('texte');
            $table->string('chemin_du_fichier')->nullable();
            $table->datetime('date_heure_supprime')->nullable();
            $table->bigInteger('id_envoyeur')->unsigned();
            $table->bigInteger('id_receveur')->unsigned();
            $table->bigInteger('id_conversation')->unsigned();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('id_envoyeur')->references('id')->on('users');
            $table->foreign('id_receveur')->references('id')->on('users');
            $table->foreign('id_conversation')->references('id')->on('conversations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
