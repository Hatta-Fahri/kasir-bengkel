<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use Illuminate\Database\Seeder;

class SparepartSeeder extends Seeder
{
    /**
     * Seed 10 sparepart bengkel yang realistis.
     * Aman dijalankan berulang kali (idempotent via updateOrCreate).
     */
    public function run(): void
    {
        $spareparts = [
            // ── Kategori: Rem ─────────────────────────────────────────────
            [
                'kode_part'     => 'SP-001',
                'nama_part'     => 'Kampas Rem Depan',
                'merek'         => 'Bendix',
                'kategori'      => 'Rem',
                'stok'          => 25,
                'stok_minimum'  => 5,
                'harga_beli'    => 85000,
                'satuan'        => 'set',
                'keterangan'    => 'Kampas rem depan universal, cocok untuk sebagian besar kendaraan',
            ],
            [
                'kode_part'     => 'SP-002',
                'nama_part'     => 'Kampas Rem Belakang',
                'merek'         => 'Bendix',
                'kategori'      => 'Rem',
                'stok'          => 20,
                'stok_minimum'  => 5,
                'harga_beli'    => 75000,
                'satuan'        => 'set',
                'keterangan'    => 'Kampas rem belakang universal',
            ],
            // ── Kategori: Filter ──────────────────────────────────────────
            [
                'kode_part'     => 'SP-003',
                'nama_part'     => 'Filter Oli Mesin',
                'merek'         => 'Sakura',
                'kategori'      => 'Filter',
                'stok'          => 40,
                'stok_minimum'  => 8,
                'harga_beli'    => 35000,
                'satuan'        => 'pcs',
                'keterangan'    => 'Filter oli standar, ganti setiap 5.000 km',
            ],
            [
                'kode_part'     => 'SP-004',
                'nama_part'     => 'Filter Udara',
                'merek'         => 'K&N',
                'kategori'      => 'Filter',
                'stok'          => 30,
                'stok_minimum'  => 5,
                'harga_beli'    => 55000,
                'satuan'        => 'pcs',
                'keterangan'    => 'Filter udara, ganti setiap 10.000 km atau setahun sekali',
            ],
            // ── Kategori: Oli ─────────────────────────────────────────────
            [
                'kode_part'     => 'SP-005',
                'nama_part'     => 'Oli Mesin 10W-40',
                'merek'         => 'Castrol',
                'kategori'      => 'Oli',
                'stok'          => 60,
                'stok_minimum'  => 10,
                'harga_beli'    => 55000,
                'satuan'        => 'liter',
                'keterangan'    => 'Oli mesin mineral 10W-40, standar kendaraan bensin',
            ],
            [
                'kode_part'     => 'SP-006',
                'nama_part'     => 'Oli Transmisi Otomatis',
                'merek'         => 'Shell',
                'kategori'      => 'Oli',
                'stok'          => 20,
                'stok_minimum'  => 5,
                'harga_beli'    => 75000,
                'satuan'        => 'liter',
                'keterangan'    => 'ATF untuk transmisi otomatis',
            ],
            // ── Kategori: Kelistrikan ─────────────────────────────────────
            [
                'kode_part'     => 'SP-007',
                'nama_part'     => 'Busi NGK Standard',
                'merek'         => 'NGK',
                'kategori'      => 'Kelistrikan',
                'stok'          => 50,
                'stok_minimum'  => 8,
                'harga_beli'    => 25000,
                'satuan'        => 'pcs',
                'keterangan'    => 'Busi standar NGK, ganti setiap 20.000 km',
            ],
            [
                'kode_part'     => 'SP-008',
                'nama_part'     => 'Aki Kering 45Ah',
                'merek'         => 'GS Astra',
                'kategori'      => 'Kelistrikan',
                'stok'          => 8,
                'stok_minimum'  => 3,
                'harga_beli'    => 550000,
                'satuan'        => 'pcs',
                'keterangan'    => 'Aki kering MF 45Ah untuk kendaraan penumpang',
            ],
            // ── Kategori: Ban & Velg ──────────────────────────────────────
            [
                'kode_part'     => 'SP-009',
                'nama_part'     => 'Ban 185/65 R15',
                'merek'         => 'Bridgestone',
                'kategori'      => 'Ban & Velg',
                'stok'          => 12,
                'stok_minimum'  => 4,
                'harga_beli'    => 650000,
                'satuan'        => 'pcs',
                'keterangan'    => 'Ban tubeless ukuran standar sedan/MPV',
            ],
            // ── Kategori: Pendingin ───────────────────────────────────────
            [
                'kode_part'     => 'SP-010',
                'nama_part'     => 'Coolant Radiator',
                'merek'         => 'Toyota Long Life',
                'kategori'      => 'Pendingin',
                'stok'          => 35,
                'stok_minimum'  => 6,
                'harga_beli'    => 45000,
                'satuan'        => 'liter',
                'keterangan'    => 'Cairan pendingin radiator siap pakai',
            ],
        ];

        foreach ($spareparts as $data) {
            Sparepart::updateOrCreate(
                ['kode_part' => $data['kode_part']],
                $data
            );
        }

        $this->command->info('✅  SparepartSeeder: ' . count($spareparts) . ' sparepart berhasil di-seed.');
    }
}
