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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 15, 2);
            $table->bigInteger('id_compte_envoyeur')->nullable()->unsigned();
            $table->bigInteger('id_compte_receveur')->nullable()->unsigned();
            $table->bigInteger('id_type_transaction')->unsigned();
            $table->bigInteger('id_etat_transaction')->unsigned();
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('id_compte_envoyeur')->references('id')->on('compte_bancaires');
            $table->foreign('id_compte_receveur')->references('id')->on('compte_bancaires');
            $table->foreign('id_type_transaction')->references('id')->on('type_transactions');
            $table->foreign('id_etat_transaction')->references('id')->on('etat_transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
