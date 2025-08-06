<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisProyekSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_proyeks')->insert([
            ['name' => 'PKLD'],
            ['name' => 'TAMBAHAN'],
        ]);
    }
}