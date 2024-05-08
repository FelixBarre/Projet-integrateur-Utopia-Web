<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EtatDemandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('etat_demandes')->insert([
            ['label' => 'Approuvée'],
            ['label' => 'Refusée'],
            ['label' => 'En attente'],
            ['label' => 'Annulée'],
        ]);
    }
}
