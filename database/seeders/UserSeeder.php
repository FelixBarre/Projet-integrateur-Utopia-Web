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
            ['nom' => 'Test',
            'prenom' => 'User-Normal',
            'telephone' => '819 555-1111',
            'no_civique' => 111,
            'rue' => 'du Cégep',
            'id_ville' => 1,
            'code_postal' => 'A1A 1A1',
            'email' => 'test1@user.com',
            'email_verified_at' => null,
            'password' => '$2y$12$9lfy/EHarww1RMJvZIuxUOwritCTwXHXEVJUg8y8lQkmbVxdsFqa6',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null],
            ['nom' => 'Test',
            'prenom' => 'User-Employe',
            'telephone' => '(819) 555-2222',
            'no_civique' => 222,
            'rue' => 'du Cégep',
            'id_ville' => 2,
            'email' => 'test2@user.com',
            'code_postal' => 'B2B 2B2',
            'email_verified_at' => null,
            'password' => '$2y$12$DJMQfUFAHz8NZyOcA2tknOn6PDUUBIDLPVxTdfStcJE088JsG7Jjm',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null],
            ['nom' => 'Test',
            'prenom' => 'User-Administrateur',
            'telephone' => '(819) 555-3333',
            'no_civique' => 333,
            'rue' => 'du Cégep',
            'id_ville' => 3,
            'email' => 'test3@user.com',
            'code_postal' => 'C3C 3C3',
            'email_verified_at' => null,
            'password' => '$2y$12$oy3I6atsrRj7pX6TZYIwuedSgEAuXAnOlJQx5xG48u/SRycMK9Nwa',
            'remember_token' => null,
            'created_at' => '1970-01-01 00:00:01',
            'updated_at' => null],
        ]);
    }
}
