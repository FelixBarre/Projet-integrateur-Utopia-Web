<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

    DB::table('transactions')->insert([
        ['montant' => 500,
        'id_compte_envoyeur' => null,
        'id_compte_receveur' => 1,
        'id_type_transaction' =>1,
        'id_etat_transaction' =>2,
        'id_facture' =>null,
        'created_at' => date('Y-m-d H:i:s')],
        ['montant' => 1500,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => null,
        'id_type_transaction' =>2,
        'id_etat_transaction' =>3,
        'id_facture' =>null,
        'created_at' => date('Y-m-d H:i:s')],
        ['montant' => 2800,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => 2,
        'id_type_transaction' =>3,
        'id_etat_transaction' =>1,
        'id_facture' =>null,
        'created_at' => date('Y-m-d H:i:s')],
        ['montant' => 1280,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => null,
        'id_type_transaction' =>4,
        'id_etat_transaction' =>3,
        'id_facture' =>3,
        'created_at' => date('Y-m-d H:i:s')]
        ]);
    }

}
