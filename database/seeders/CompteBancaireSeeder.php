<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompteBancaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('compte_bancaires')->insert([
            ['nom' => 'compteExemple',
            'solde' => 1299.58,
            'taux_interet' => 0.01,
            'id_user' => 1]
        ]);
    }
}
