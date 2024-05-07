<?php

namespace Database\Seeders;

use App\Models\Fournisseur;
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
        $this->call([
            VilleSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            RolesUsersSeeder::class,
            FournisseurSeeder::class,
            CompteBancaireSeeder::class,
            TypeTransactionSeeder::class,
            EtatTransactionSeeder::class,
            TransactionSeeder::class,
            TypeDemandeSeeder::class,
            EtatDemandeSeeder::class,
            DemandeSeeder::class,
            ConversationSeeder::class,
            MessageSeeder::class
        ]);

    }
}
