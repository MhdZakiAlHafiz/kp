<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User Admin (role_id 1)
        User::firstOrCreate(
            ['email' => 'admintsibrks@brksyariah.co.id'],
            [
                'name' => 'Admin TSI BRKSyariah',
                'password' => Hash::make('admintsibrks'), // Menggunakan Hash::make untuk keamanan
                'status' => 'approved',
                'role_id' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Contoh user untuk bidang 'planning'
        User::firstOrCreate(
            ['email' => 'plan@example.com'],
            [
                'name' => 'PIC Planning 1',
                'bidang' => 'planning',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved', // PERBAIKAN: Memberikan status default
                'role_id' => 2, // Memberikan role_id default
            ]
        );

        // Contoh user untuk bidang 'development'
        User::firstOrCreate(
            ['email' => 'dev@example.com'],
            [
                'name' => 'PIC Development 1',
                'bidang' => 'development',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved', // PERBAIKAN: Memberikan status default
                'role_id' => 2, // Memberikan role_id default
            ]
        );

        // Contoh user lain tanpa bidang spesifik jika diperlukan
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'bidang' => null,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'submitted', // PERBAIKAN: Memberikan status 'submitted' agar bisa di-approve
                'role_id' => 2, // Memberikan role_id default
            ]
        );
    }
}
