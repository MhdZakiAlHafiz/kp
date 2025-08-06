<?php

namespace Database\Seeders;

use App\Models\Pic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pics = [
            'Ronaldy',
            'Lutfi',
            'Wildan',
            'Ori',
            'Bima',
            'Koiri',
            'Ardi',
            'Nanda',
            'Fikri',
            'Zahra',
            'Rizky',
            'Dinda',
            'Andi',
            'Tari',
            'Fajar',
            'Mega',
            'Putra',
            'Salma',
            'Iqbal',
            'Novi'
        ];

        foreach ($pics as $picName) {
            Pic::firstOrCreate(['name' => $picName]);
        }
    }
}
