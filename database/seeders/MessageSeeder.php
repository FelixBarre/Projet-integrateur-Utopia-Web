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
                'created_at' => '2024-05-02 11:10:37',
                'updated_at' => '2024-05-02 11:10:37',
                'texte'=>'Bonjour!',
                'id_envoyeur'=>1,
                'id_receveur'=>2,
                'id_conversation'=>1
            ],
            [
                'created_at' => '2024-05-02 20:14:37',
                'updated_at' => '2024-05-02 20:14:37',
                'texte'=>'Bonsoir',
                'id_envoyeur'=>2,
                'id_receveur'=>1,
                'id_conversation'=>1
            ]
        ]);
    }
}
