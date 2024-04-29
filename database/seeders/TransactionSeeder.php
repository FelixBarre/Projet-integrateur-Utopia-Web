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
        'id_etat_transaction' =>1]
        ]);
    }

}
