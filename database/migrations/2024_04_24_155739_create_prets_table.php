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
        Schema::create('prets', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('montant', 15, 2);
            $table->date('date_debut');
            $table->date('date_echeance');
            $table->bigInteger('id_compte')->unsigned();
        });

        Schema::table('prets', function (Blueprint $table) {
            $table->foreign('id_compte')->references('id')->on('compte_bancaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prets');
    }
};
