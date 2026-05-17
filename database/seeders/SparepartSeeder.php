<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Seed data sparepart realistis untuk testing lengkap sistem.
     * Variasi stok sengaja dibuat: ada yang banyak, sedikit, dan habis.
     */
    public function run(): void
    {
        $spareparts = [
            ['kode_part' => 'SP-0001', 'nama_part' => 'Filter Oli',          'merek' => 'Sakura',    'kategori' => 'Filter',       'harga_beli' => 35000,  'stok' => 25,  'stok_minimum' => 5,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0002', 'nama_part' => 'Busi NGK',            'merek' => 'NGK',       'kategori' => 'Pengapian',    'harga_beli' => 45000,  'stok' => 3,   'stok_minimum' => 5,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0003', 'nama_part' => 'Kampas Rem Depan',    'merek' => 'Bendix',    'kategori' => 'Rem',          'harga_beli' => 85000,  'stok' => 8,   'stok_minimum' => 3,  'satuan' => 'set'],
            ['kode_part' => 'SP-0004', 'nama_part' => 'Oli Mesin 10W-40',    'merek' => 'Fastron',   'kategori' => 'Pelumas',      'harga_beli' => 65000,  'stok' => 0,   'stok_minimum' => 10, 'satuan' => 'liter'],
            ['kode_part' => 'SP-0005', 'nama_part' => 'V-Belt',              'merek' => 'Gates',     'kategori' => 'Transmisi',    'harga_beli' => 120000, 'stok' => 15,  'stok_minimum' => 3,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0006', 'nama_part' => 'Air Filter',          'merek' => 'Denso',     'kategori' => 'Filter',       'harga_beli' => 55000,  'stok' => 2,   'stok_minimum' => 3,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0007', 'nama_part' => 'Kampas Kopling',      'merek' => 'LHK',       'kategori' => 'Kopling',      'harga_beli' => 95000,  'stok' => 20,  'stok_minimum' => 5,  'satuan' => 'set'],
            ['kode_part' => 'SP-0008', 'nama_part' => 'Bearing Roda Depan',  'merek' => 'SKF',       'kategori' => 'Bearing',      'harga_beli' => 150000, 'stok' => 6,   'stok_minimum' => 2,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0009', 'nama_part' => 'Shock Absorber Depan','merek' => 'KYB',       'kategori' => 'Suspensi',     'harga_beli' => 350000, 'stok' => 4,   'stok_minimum' => 2,  'satuan' => 'pcs'],
            ['kode_part' => 'SP-0010', 'nama_part' => 'Radiator Cap',        'merek' => 'Tora',      'kategori' => 'Pendingin',    'harga_beli' => 25000,  'stok' => 12,  'stok_minimum' => 3,  'satuan' => 'pcs'],
        ];

        $count = 0;
        foreach ($spareparts as $data) {
            Sparepart::updateOrCreate(
                ['kode_part' => $data['kode_part']],
                array_merge($data, ['keterangan' => null])
            );
            $count++;
        }

        $this->command->info("✅ SparepartSeeder selesai: {$count} sparepart");

        // Ringkasan stok
        $menipis = collect($spareparts)->filter(fn ($s) => $s['stok'] <= $s['stok_minimum'])->count();
        $habis   = collect($spareparts)->filter(fn ($s) => $s['stok'] === 0)->count();
        $this->command->table(
            ['Kondisi', 'Jumlah'],
            [
                ['Total Sparepart', $count],
                ['Stok Menipis (≤ minimum)', $menipis],
                ['Stok Habis', $habis],
            ]
        );
    }
}
