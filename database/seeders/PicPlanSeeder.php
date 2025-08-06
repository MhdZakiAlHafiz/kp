<?php

namespace Database\Seeders;

use App\Models\PicPlan; // Import model PicPlan
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PicPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $picPlanNames = [
            'Ronaldy',
            'Lutfi',
            'Koiri',
            'Tari',
            'Fajar',
            'Mega',
            'Putra',
            'Salma',
            'Iqbal',
            'Novi'
        ];

        foreach ($picPlanNames as $name) {
            PicPlan::firstOrCreate(['name' => $name]);
        }
    }
}
