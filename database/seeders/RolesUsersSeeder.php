<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles_users')->insert([
            ['id_role' => 3,
            'id_user' => 1],
            ['id_role' => 2,
            'id_user' => 2],
            ['id_role' => 1,
            'id_user' => 3]
        ]);
    }
}
