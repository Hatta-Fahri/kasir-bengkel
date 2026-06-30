<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HistoricalMayTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder untuk menghasilkan transaksi penjualan historis bulan Mei (Bulan 5)
     * sebanyak 3-5 transaksi per hari secara acak selama 30 hari.
     */
    public function run(): void
    {
        // 1. Pastikan ada kasir / user
        $kasir = User::where('role', 'kasir')->first() ?? User::first();
        if (!$kasir) {
            $this->command->warn('⚠️ Tidak ada data user/kasir. Silakan jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // 2. Ambil semua sparepart yang ada di database
        $spareparts = Sparepart::all();
        if ($spareparts->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada data sparepart. Silakan input atau jalankan SparepartSeeder terlebih dahulu.');
            return;
        }

        $totalTransaksi = 0;
        $tahun = Carbon::now()->year;

        // Jika sekarang di awal tahun (misal Januari), bulan 5 tahun lalu. Jika di atas bulan 5, tahun ini.
        $tahunMei = (Carbon::now()->month < 5) ? $tahun - 1 : $tahun;

        $this->command->info("🚀 Memulai generate transaksi historis untuk bulan Mei {$tahunMei} (30 hari)...");

        // Loop selama 30 hari di bulan Mei (1 Mei - 30 Mei)
        for ($hari = 1; $hari <= 30; $hari++) {
            $tglStr = sprintf('%04d-05-%02d', $tahunMei, $hari);

            // Acak jumlah transaksi per hari: 3 sampai 5 transaksi
            $jumlahTrxHariIni = rand(3, 5);

            for ($urutan = 1; $urutan <= $jumlahTrxHariIni; $urutan++) {
                $totalTransaksi++;
                $noStruk = sprintf('TRX-%04d05%02d-%03d', $tahunMei, $hari, $urutan);

                // Acak jam transaksi antara jam 08:00 sampai 17:00
                $jam = sprintf('%02d:%02d:%02d', rand(8, 16), rand(0, 59), rand(0, 59));
                $timestamp = "{$tglStr} {$jam}";

                // Tentukan tipe transaksi: 70% penjualan biasa, 30% servis
                $isServis = rand(1, 10) <= 3;
                $tipeTransaksi = $isServis ? 'servis' : 'penjualan';
                $platNomor = $isServis ? $this->randomPlat() : null;
                $jenisMobil = $isServis ? $this->randomMobil() : null;
                $ongkosJasa = $isServis ? rand(5, 25) * 10000 : 0; // Rp 50.000 - Rp 250.000

                // Pilih 1 sampai 3 jenis sparepart secara acak untuk transaksi ini
                $jumlahItem = min(rand(1, 3), $spareparts->count());
                $selectedSpareparts = $spareparts->random($jumlahItem);

                $subtotalSparepart = 0;
                $itemsToSave = [];

                foreach ($selectedSpareparts as $sp) {
                    $qty = rand(1, 3);
                    $hargaBeli = (float) $sp->harga_beli;
                    $hargaJual = (float) $sp->harga_jual; // Mengambil dari accessor hargaJual (+10%)
                    $subtotalItem = $qty * $hargaJual;

                    $subtotalSparepart += $subtotalItem;

                    $itemsToSave[] = [
                        'sparepart_id' => $sp->id,
                        'qty' => $qty,
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'subtotal' => $subtotalItem,
                    ];
                }

                $totalBayar = $subtotalSparepart + $ongkosJasa;

                // Tentukan metode pembayaran: 80% cash, 20% qris
                $metodePembayaran = rand(1, 10) <= 8 ? 'cash' : 'qris';
                $uangDiterima = null;
                $kembalian = null;

                if ($metodePembayaran === 'cash') {
                    $uangDiterima = $this->roundUp($totalBayar);
                    $kembalian = $uangDiterima - $totalBayar;
                }

                // Simpan atau update header transaksi (Idempotent berdasarkan no_struk)
                $trx = Transaction::firstOrNew(['no_struk' => $noStruk]);
                $trx->forceFill([
                    'kasir_id'           => $kasir->id,
                    'tipe_transaksi'     => $tipeTransaksi,
                    'plat_nomor'         => $platNomor,
                    'jenis_mobil'        => $jenisMobil,
                    'ongkos_jasa'        => $ongkosJasa,
                    'subtotal_sparepart' => $subtotalSparepart,
                    'total_bayar'        => $totalBayar,
                    'metode_pembayaran'  => $metodePembayaran,
                    'uang_diterima'      => $uangDiterima,
                    'kembalian'          => $kembalian,
                    'status'             => 'selesai',
                    'catatan'            => null,
                    'created_at'         => $timestamp,
                    'updated_at'         => $timestamp,
                ]);
                $trx->timestamps = false;
                $trx->save();

                // Hapus detail lama jika transaksi sudah ada sebelumnya
                $trx->details()->delete();

                // Simpan detail transaksi
                foreach ($itemsToSave as $item) {
                    $detail = new TransactionDetail();
                    $detail->forceFill([
                        'transaction_id'            => $trx->id,
                        'sparepart_id'              => $item['sparepart_id'],
                        'qty'                       => $item['qty'],
                        'harga_beli_saat_transaksi' => $item['harga_beli'],
                        'harga_jual_saat_transaksi' => $item['harga_jual'],
                        'subtotal'                  => $item['subtotal'],
                        'created_at'                => $timestamp,
                        'updated_at'                => $timestamp,
                    ]);
                    $detail->timestamps = false;
                    $detail->save();
                }
            }
        }

        $this->command->info("✅ Berhasil membuat {$totalTransaksi} transaksi penjualan historis untuk bulan Mei {$tahunMei}!");
    }

    /**
     * Bulatkan ke atas ke kelipatan Rp 5.000 untuk simulasi uang diterima (Cash).
     */
    private function roundUp(float $amount): float
    {
        return ceil($amount / 5000) * 5000;
    }

    private function randomPlat(): string
    {
        $huruf = ['B', 'D', 'F', 'H', 'L', 'N', 'W'];
        $akhir = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'JK', 'PR', 'SI'];

        return $huruf[array_rand($huruf)]
            . ' '
            . rand(1000, 9999)
            . ' '
            . $akhir[array_rand($akhir)];
    }

    private function randomMobil(): string
    {
        $mobil = [
            'Toyota Avanza', 'Toyota Innova Zenix', 'Toyota Rush', 'Toyota Calya',
            'Honda Brio Satya', 'Honda HR-V', 'Honda CR-V', 'Honda Mobilio',
            'Mitsubishi Xpander', 'Mitsubishi Pajero Sport',
            'Suzuki Ertiga Hybrid', 'Suzuki XL7', 'Daihatsu Sigra', 'Daihatsu Terios',
            'Hyundai Stargazer', 'Wuling Almaz',
        ];

        return $mobil[array_rand($mobil)];
    }
}
