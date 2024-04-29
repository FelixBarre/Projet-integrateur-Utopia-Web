<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['name' => "usernameTest",
            'nom' => 'Nom-Test',
            'prenom' => 'Prenom-Test',
            'no_civique' => 123,
            'rue' => 'du Cégep',
            'id_ville' => 1,
            'email' => 'test@bidon.ca',
            'email_verified_at' => null,
            'password' => 'abcd1234',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null]
        ]);
    }
}
