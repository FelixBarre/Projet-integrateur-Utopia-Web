<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('credits')->insert([
            ['nom' => 'carte de crédit',
            'limite' => 3000,
            'est_valide' => 1,
            'id_compte' => 8]
        ]);
    }
}
