<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('jenis_surats')->insert([
            ['name' => 'BRD'],
            ['name' => 'CR'],
        ]);
    }
}
