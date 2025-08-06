<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
    // database/seeders/DatabaseSeeder.php
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,     // Ini harus dijalankan duluan
            UserSeeder::class,
            JenisSuratSeeder::class,
            OwnerSeeder::class,
            JenisProyekSeeder::class,    // Ini setelah RoleSeeder
            // ... seeder lainnya (PicDevSeeder, PicPlanSeeder)
        ]);
    }
}
