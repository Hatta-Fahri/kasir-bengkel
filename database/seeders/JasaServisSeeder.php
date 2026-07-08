<?php

namespace Database\Seeders;

use App\Models\JasaServis;
use Illuminate\Database\Seeder;

class JasaServisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_jasa' => 'Cuci Evaporator',           'estimasi_biaya' => 150000, 'keterangan' => null],
            ['nama_jasa' => 'Isi Freon R134a',            'estimasi_biaya' => 350000, 'keterangan' => null],
            ['nama_jasa' => 'Ganti Dryer',                'estimasi_biaya' => 250000, 'keterangan' => null],
            ['nama_jasa' => 'Cuci Filter Kabin',          'estimasi_biaya' => 75000,  'keterangan' => null],
            ['nama_jasa' => 'Isi Freon R22',              'estimasi_biaya' => 300000, 'keterangan' => null],
            ['nama_jasa' => 'Perbaikan Kompresor',        'estimasi_biaya' => 500000, 'keterangan' => 'Harga bisa berbeda tergantung kerusakan'],
            ['nama_jasa' => 'Ganti Selang Freon',         'estimasi_biaya' => 200000, 'keterangan' => null],
            ['nama_jasa' => 'Service AC Ringan',          'estimasi_biaya' => 120000, 'keterangan' => 'Termasuk cuci dan cek freon'],
            ['nama_jasa' => 'Bongkar Pasang Kondensor',   'estimasi_biaya' => 400000, 'keterangan' => null],
            ['nama_jasa' => 'Flush Sistem AC',            'estimasi_biaya' => 250000, 'keterangan' => null],
        ];

        foreach ($data as $item) {
            JasaServis::create(array_merge($item, ['is_aktif' => true]));
        }
    }
}
