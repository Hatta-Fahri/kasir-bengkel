<?php

namespace Database\Seeders;

use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Seed transaksi historis selama 12 bulan ke belakang.
     *
     * Tujuan utama: menyediakan data histori penjualan yang cukup agar
     * PredictionService (Prophet) dapat menghasilkan prediksi yang valid.
     *
     * Pola data per sparepart (realistis bengkel):
     *  - SP-001 Kampas Rem Depan    → tren stabil naik (permintaan tinggi)
     *  - SP-002 Kampas Rem Belakang → tren stabil
     *  - SP-003 Filter Oli Mesin    → permintaan sangat tinggi (ganti rutin)
     *  - SP-004 Filter Udara        → tren musiman, lebih ramai semester 2
     *  - SP-005 Oli Mesin 10W-40    → permintaan paling tinggi
     *  - SP-006 Oli Transmisi       → permintaan rendah (tidak semua kendaraan)
     *  - SP-007 Busi NGK            → tren stabil
     *  - SP-008 Aki Kering          → permintaan rendah, sporadis
     *  - SP-009 Ban 185/65 R15      → permintaan rendah
     *  - SP-010 Coolant Radiator    → permintaan sedang
     *
     * Data SP-003 sengaja memiliki histori sangat panjang (12 bln) dan volume
     * tinggi → Prophet pasti berhasil & menghasilkan prediksi akurat.
     */
    public function run(): void
    {
        // Pastikan sparepart sudah ada
        if (Sparepart::count() === 0) {
            $this->command->warn('⚠️  Tidak ada sparepart. Jalankan SparepartSeeder terlebih dahulu.');
            return;
        }

        $kasir = User::where('role', 'kasir')->first()
            ?? User::first();

        if (! $kasir) {
            $this->command->warn('⚠️  Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Ambil semua sparepart ke dalam map kode_part → model
        $spareparts = Sparepart::all()->keyBy('kode_part');

        // ── Template qty per-bulan per-sparepart ─────────────────────────────
        // Index 0 = 12 bulan lalu, index 11 = bulan lalu
        // qty ini adalah TOTAL yang terjual dalam 1 bulan (dibagi ke beberapa transaksi)
        $template = [
            'SP-001' => [10, 12, 11, 13, 12, 14, 13, 15, 14, 16, 15, 17], // tren naik
            'SP-002' => [ 8,  9,  8, 10,  9, 11, 10, 10, 11, 12, 11, 13], // stabil-naik
            'SP-003' => [18, 20, 22, 19, 21, 23, 20, 24, 22, 25, 23, 26], // sangat tinggi
            'SP-004' => [ 5,  6,  5,  6,  7,  8,  9, 10, 11, 10,  9,  8], // musiman S2
            'SP-005' => [25, 28, 26, 30, 27, 32, 29, 34, 31, 36, 33, 38], // tertinggi
            'SP-006' => [ 3,  4,  3,  5,  4,  3,  5,  4,  6,  5,  4,  6], // rendah
            'SP-007' => [ 7,  8,  9,  8,  9, 10,  9, 11, 10, 12, 11, 13], // stabil
            'SP-008' => [ 1,  2,  1,  2,  1,  3,  2,  1,  2,  3,  2,  3], // sporadis
            'SP-009' => [ 2,  3,  2,  3,  2,  4,  3,  2,  3,  4,  3,  4], // rendah
            'SP-010' => [ 6,  7,  6,  8,  7,  9,  8,  9,  8, 10,  9, 11], // sedang
        ];

        $counter     = 0; // nomor urut struk
        $now         = Carbon::now();
        $bulanAwal   = $now->copy()->subMonths(12)->startOfMonth();

        for ($monthOffset = 0; $monthOffset < 12; $monthOffset++) {
            $bulan = $bulanAwal->copy()->addMonths($monthOffset);

            // Kumpulkan item yang akan dijual bulan ini
            $itemsBulanIni = [];

            foreach ($template as $kodePart => $qtyPerBulan) {
                $sp = $spareparts->get($kodePart);
                if (! $sp) {
                    continue;
                }

                $sisaQty = $qtyPerBulan[$monthOffset];

                // Pecah total qty bulan ini menjadi beberapa transaksi (2-4 per bulan)
                // agar data lebih realistis & tidak satu transaksi raksasa
                $jumlahTrx = rand(2, 4);
                $parts     = $this->splitQty($sisaQty, $jumlahTrx);

                foreach ($parts as $idx => $qty) {
                    // Sebar tanggal transaksi merata dalam 1 bulan
                    $dayOffset = (int) round(($idx / $jumlahTrx) * ($bulan->daysInMonth - 1));
                    $tglTrx    = $bulan->copy()->addDays($dayOffset);

                    $itemsBulanIni[] = [
                        'sp'     => $sp,
                        'qty'    => $qty,
                        'tgl'    => $tglTrx,
                    ];
                }
            }

            // Kelompokkan item berdasarkan tanggal → buat transaksi per hari
            $grouped = collect($itemsBulanIni)->groupBy(fn ($i) => $i['tgl']->toDateString());

            foreach ($grouped as $tglStr => $items) {
                $counter++;
                $noStruk = sprintf(
                    'TRX-%s-%03d',
                    Carbon::parse($tglStr)->format('Ymd'),
                    $counter
                );

                // Hitung subtotal sparepart
                $subtotalSparepart = $items->sum(fn ($i) => $i['qty'] * $i['sp']->harga_jual);

                // Untuk variasi: sebagian transaksi bertipe servis
                $tipe       = ($counter % 3 === 0) ? 'servis' : 'penjualan';
                $ongkos     = $tipe === 'servis' ? rand(50, 200) * 1000 : 0;
                $totalBayar = $subtotalSparepart + $ongkos;

                // Variasi metode pembayaran
                $metode       = ($counter % 5 === 0) ? 'qris' : 'cash';
                $uangDiterima = $metode === 'cash' ? $this->roundUp($totalBayar) : null;
                $kembalian    = $metode === 'cash' ? $uangDiterima - $totalBayar : null;

                // Timestamp historis — set manual agar created_at sesuai bulan penjualan
                $jam = sprintf('%02d:%02d:00', rand(8, 17), rand(0, 59));
                $createdAt = $tglStr . ' ' . $jam;

                // Gunakan firstOrNew + forceFill + save supaya bisa set created_at
                // (created_at tidak ada di $fillable Transaction, tapi forceFill melewati guard)
                $trx = Transaction::firstOrNew(['no_struk' => $noStruk]);
                $trx->forceFill([
                    'kasir_id'           => $kasir->id,
                    'tipe_transaksi'     => $tipe,
                    'plat_nomor'         => $tipe === 'servis' ? $this->randomPlat() : null,
                    'jenis_mobil'        => $tipe === 'servis' ? $this->randomMobil() : null,
                    'ongkos_jasa'        => $ongkos,
                    'subtotal_sparepart' => $subtotalSparepart,
                    'total_bayar'        => $totalBayar,
                    'metode_pembayaran'  => $metode,
                    'uang_diterima'      => $uangDiterima,
                    'kembalian'          => $kembalian,
                    'status'             => 'selesai',
                    'catatan'            => null,
                    'created_at'         => $createdAt,
                    'updated_at'         => $createdAt,
                ]);
                // Nonaktifkan auto-update timestamps agar created_at tidak ditimpa
                $trx->timestamps = false;
                $trx->save();

                // Hapus detail lama kalau trx sudah ada (idempotent)
                $trx->details()->delete();

                foreach ($items as $item) {
                    /** @var Sparepart $sp */
                    $sp = $item['sp'];

                    TransactionDetail::create([
                        'transaction_id'            => $trx->id,
                        'sparepart_id'              => $sp->id,
                        'qty'                       => $item['qty'],
                        'harga_beli_saat_transaksi' => $sp->harga_beli,
                        'harga_jual_saat_transaksi' => $sp->harga_jual,
                        'subtotal'                  => $item['qty'] * $sp->harga_jual,
                    ]);
                }
            }
        }

        $this->command->info("✅  TransactionSeeder: {$counter} transaksi berhasil di-seed (12 bulan historis).");
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Pecah qty total menjadi $n bagian acak (masing-masing minimal 1).
     *
     * @return int[]
     */
    private function splitQty(int $total, int $n): array
    {
        // Kalau total lebih kecil dari jumlah potongan, cukup kembalikan apa adanya
        if ($n <= 1 || $total <= $n) {
            return [$total];
        }

        $parts     = [];
        $remaining = $total;

        for ($i = 0; $i < $n - 1; $i++) {
            // Sisa potongan yang belum dibuat: ($n - $i - 1) buah, masing-masing minimal 1
            $maxVal = $remaining - ($n - $i - 1);
            $val    = rand(1, max(1, $maxVal));
            $parts[]   = $val;
            $remaining -= $val;
        }

        // Sisa terakhir pasti >= 1 karena kita sudah pastikan di atas
        $parts[] = $remaining;

        return $parts;
    }

    /**
     * Bulatkan ke atas ke kelipatan Rp 5.000 untuk simulasi uang diterima.
     */
    private function roundUp(float $amount): float
    {
        return ceil($amount / 5000) * 5000;
    }

    private function randomPlat(): string
    {
        $huruf = ['AB', 'B', 'D', 'F', 'H', 'K', 'L', 'R', 'S', 'W'];
        $akhir = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        return $huruf[array_rand($huruf)]
            . ' '
            . rand(1000, 9999)
            . ' '
            . $akhir[array_rand($akhir)];
    }

    private function randomMobil(): string
    {
        $mobil = [
            'Toyota Avanza', 'Toyota Innova', 'Toyota Rush',
            'Honda Brio', 'Honda Jazz', 'Honda CR-V',
            'Mitsubishi Xpander', 'Mitsubishi Pajero',
            'Suzuki Ertiga', 'Daihatsu Xenia', 'Daihatsu Terios',
            'Nissan Grand Livina', 'Wuling Almaz',
        ];

        return $mobil[array_rand($mobil)];
    }
}
