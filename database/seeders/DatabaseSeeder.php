<?php

namespace Database\Seeders;

use App\Models\EtatTransaction;
use App\Models\Transaction;
use App\Models\TypeTransaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $this->call([
            VilleSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            RolesUsersSeeder::class
        ]);

        $this->call([
            User::class,
            TypeTransaction::class,
            EtatTransaction::class
            ]);
    }
}
