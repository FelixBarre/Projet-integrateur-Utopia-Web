<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('messages')->insert([
            [
                'texte'=>true,
                'id_envoyeur'=>1,
                'id_receveur'=>2,
                'id_conversation'=>1
            ],
            [
                'texte'=>true,
                'id_envoyeur'=>2,
                'id_receveur'=>1,
                'id_conversation'=>1
            ]
        ]);
    }
}
