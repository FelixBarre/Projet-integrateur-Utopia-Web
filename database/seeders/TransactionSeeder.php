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
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => 1,
        'id_type_transaction' =>1,
        'id_etat_transaction' =>1],
        ['montant' => 1500,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => 1,
        'id_type_transaction' =>2,
        'id_etat_transaction' =>2],
        ['montant' => 2800,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => 1,
        'id_type_transaction' =>3,
        'id_etat_transaction' =>3],
        ['montant' => 280,
        'id_compte_envoyeur' => 1,
        'id_compte_receveur' => 1,
        'id_type_transaction' =>1,
        'id_etat_transaction' =>1]
        ]);
    }

}
