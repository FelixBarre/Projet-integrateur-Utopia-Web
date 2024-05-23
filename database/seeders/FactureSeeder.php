<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('factures')->insert([
            ['nom' => 'Facture Saaq',
            'description' =>'Renouvelement Immatriculation',
            'montant_defini' => 85.00,
            'jour_du_mois' =>5,
            'id_fournisseur' =>1],
            ['nom' => 'Facture Hydro',
            'description' =>'Paiement de facture hydro',
            'montant_defini' => 150.00,
            'jour_du_mois' =>01,
            'id_fournisseur' =>2],
            ['nom' => 'Facture Bell',
            'description' =>'Renouvelement téléphonique de forfait de base',
            'montant_defini' => 100.00,
            'jour_du_mois' =>15,
            'id_fournisseur' =>3],

            ]);
    }
}
