<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('owners')->insert([
            ['name' => 'Divisi TSI'],
            ['name' => 'Divisi OPS'],
            ['name' => 'Divisi MDM'],
            ['name' => 'Biro Kepegawaian'],
        ]);
    }
}