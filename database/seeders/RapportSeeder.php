<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RapportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rapports')->insert([
            [
                'titre' => 'Mon premier rapport',
                'description' => 'Voici mon premier rapport',
                'date_debut' => '2020-01-01',
                'date_fin' => '2020-05-05',
                'date_creation' => '2024-05-05',
                'chemin_du_fichier' => '/rapports/fichiers/monrapport.pdf',
                'id_employe' => 1
            ],
            [
                'titre' => 'Mon deuxième rapport',
                'description' => 'Voici mon deuxième rapport',
                'date_debut' => '2020-01-01',
                'date_fin' => '2020-05-05',
                'date_creation' => '2024-05-05',
                'chemin_du_fichier' => '/rapports/fichiers/monrapport2.pdf',
                'id_employe' => 1
            ]
        ]);
    }
}
