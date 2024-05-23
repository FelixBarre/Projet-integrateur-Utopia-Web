<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PretSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prets')->insert([
            ['nom' => 'Voyage',
            'montant' => 500,
            'date_debut' => '2024-08-25',
            'date_echeance' => '2024-12-25',
            'est_valide' => 1,
            'id_compte' => 7],
        ]);
    }
}
