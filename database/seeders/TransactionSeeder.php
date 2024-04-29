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
        'id_compte_envoyeur' => '00000',
        'id_compte_receveur' => 'Paquet de 10 crayons de marque HB',
        'id_type_transaction' =>1,
        'id_etat_transaction' =>1]
        ]);
    }

}
