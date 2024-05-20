<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Ville;

class VilleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataCSV = fopen(base_path('database/csv/MUN.csv'), 'r');
        $rangeeTitre = true;
        while(($data = fgetcsv($dataCSV)) !== false) {
            if (!$rangeeTitre) {
                Ville::create([
                    'nom' => $data['1']
                ]);
            }
            $rangeeTitre = false;
        }
        fclose($dataCSV);
    }
}
