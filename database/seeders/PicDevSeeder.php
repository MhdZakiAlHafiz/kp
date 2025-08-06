<?php

namespace Database\Seeders;

use App\Models\PicDev; // Import model PicDev
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PicDevSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $picDevNames = [
            'Wildan',
            'Ori',
            'Bima',
            'Ardi',
            'Nanda',
            'Fikri',
            'Zahra',
            'Rizky',
            'Dinda',
            'Andi'
        ];

        foreach ($picDevNames as $name) {
            PicDev::firstOrCreate(['name' => $name]);
        }
    }
}
