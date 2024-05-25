<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FournisseurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('fournisseurs')->insert([
            ['nom' => 'SAAQ', 'description'=>'Société de l\'assurance automobile du québec'],
            ['nom' => 'Hydro Sherbrooke', 'description'=>'Hydro Sherbrooke'],
            ['nom' => 'Bell', 'description'=>'Bell audiovisuel'],
            //['nom' => 'ARC', 'description'=>'Agence de revenus du Canada'],
            //['nom' => 'Revenu Québec', 'description'=>'Agence de revenus du Québec']
        ]);
    }

}
