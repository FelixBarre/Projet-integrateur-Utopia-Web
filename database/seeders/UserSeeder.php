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
            ['nom' => 'Nom-Test',
            'prenom' => 'Prenom-Test',
            'telephone' => '(819) 555-5555',
            'no_civique' => 123,
            'rue' => 'du Cégep',
            'id_ville' => 1,
            'code_postal' => 'A1A 1A1',
            'email' => '123@123.123',
            'email_verified_at' => null,
            'password' => '$2y$12$UVD4M7haq2UOgzyoukDHgOMVQwiOaZVsv1Z.cA4Nd3gYE1w4KiVta',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null],
            ['nom' => 'Nom-Test',
            'prenom' => 'Prenom-Test',
            'telephone' => '(819) 555-5555',
            'no_civique' => 123,
            'rue' => 'du Cégep',
            'id_ville' => 1,
            'email' => '456@456.456',
            'code_postal' => 'A1A 1A1',
            'email_verified_at' => null,
            'password' => '$2y$12$Og5cEf0tYCXkUajniy6zXu99AZ7BlytdrxLZ92Gu6QMMlp3h1C4cG',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null]
        ]);
    }
}
