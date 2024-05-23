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
            ['nom' => 'ChequeUser1',
            'solde' => 11299.58,
            'taux_interet' => 0.00,
            'est_valide' => 1,
            'id_user' => 1],
            ['nom' => 'EpargneUser1',
            'solde' => 5699.58,
            'taux_interet' => 0.02,
            'est_valide' => 1,
            'id_user' => 1],
            ['nom' => 'ChèqueUser2',
            'solde' => 878.58,
            'taux_interet' => 0.00,
            'est_valide' => 1,
            'id_user' => 2],
            ['nom' => 'EpargneUser2',
            'solde' => 987.58,
            'taux_interet' => 0.03,
            'est_valide' => 1,
            'id_user' => 2],
            ['nom' => 'ChèqueUser3',
            'solde' => 4299.58,
            'taux_interet' => 0.00,
            'est_valide' => 1,
            'id_user' => 3],
            ['nom' => 'EpargneUser3',
            'solde' => 500.58,
            'taux_interet' => 0.03,
            'est_valide' => 1,
            'id_user' => 3],
            ['nom' => 'PretVoyage',
            'solde' => 5000,
            'taux_interet' => 2.00,
            'est_valide' => 1,
            'id_user' => 1],
            ['nom' => 'carte de crédit',
            'solde' => 19.99,
            'taux_interet' => 0.50,
            'est_valide' => 1,
            'id_user' => 1]
        ]);
    }
}
