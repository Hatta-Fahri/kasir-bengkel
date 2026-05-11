<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed akun default: 1 Admin dan 1 Kasir.
     * Menggunakan updateOrCreate agar aman dijalankan berulang kali (idempotent).
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bengkel.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@bengkel.com'],
            [
                'name'     => 'Kasir Satu',
                'password' => Hash::make('password'),
                'role'     => 'kasir',
            ]
        );
    }
}
