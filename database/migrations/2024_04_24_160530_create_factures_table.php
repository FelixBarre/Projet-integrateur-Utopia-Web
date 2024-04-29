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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('description');
            $table->decimal('montant_defini', 15, 2);
            $table->integer('jour_du_mois');
            $table->bigInteger('id_fournisseur')->unsigned();
        });

        Schema::table('factures', function (Blueprint $table) {
            $table->foreign('id_fournisseur')->references('id')->on('fournisseurs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
