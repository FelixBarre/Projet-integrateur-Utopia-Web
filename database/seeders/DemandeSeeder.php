<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('demandes')->insert([
            ['date_demande' => '2020-05-01',
            'raison' => "auto",
            'montant' => 20000,
            'id_etat_demande' => 3,
            'id_demandeur' => 1,
            'id_type_demande' => 1],
            ['date_demande' => '2020-06-19',
            'raison' => "Hypothèque",
            'montant' => 315000,
            'id_etat_demande' => 3,
            'id_demandeur' => 2,
            'id_type_demande' => 1],
            ['date_demande' => '2023-08-25',
            'raison' => "Voyage",
            'montant' => 20000,
            'id_etat_demande' => 3,
            'id_demandeur' => 1,
            'id_type_demande' => 1]
        ]);
    }
}
