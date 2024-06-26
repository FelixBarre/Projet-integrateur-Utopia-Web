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
        Schema::create('credits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('limite', 15, 2);
            $table->boolean('est_valide')->nullable()->default(true);
            $table->bigInteger('id_compte')->unsigned();
        });

        Schema::table('credits', function (Blueprint $table) {
            $table->foreign('id_compte')->references('id')->on('compte_bancaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credits');
    }
};
