<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeDemandeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_demandes')->insert([
            ['label' => 'Demande de Prêt'],
            ['label' => 'Demande de désactivation de compte utilisateur'],
            ['label' => 'Demande de désactivation de compte bancaire']
        ]);
    }
}
