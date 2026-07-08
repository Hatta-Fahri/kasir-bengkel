<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan wajib dijaga karena ada foreign-key dependency:
     *   1. UserSeeder      → tabel users (kasir & admin)
     *   2. SparepartSeeder → tabel spareparts
     *   3. SparepartAcSeeder → tabel spareparts (khusus sparepart AC)
     *   4. TransactionSeeder → tabel transactions + transaction_details
     *
     * Semua seeder bersifat idempotent (aman dijalankan berulang kali).
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SparepartSeeder::class,
            SparepartAcSeeder::class,
            TransactionSeeder::class,
            HistoricalMayTransactionSeeder::class,
        ]);
    }
}
