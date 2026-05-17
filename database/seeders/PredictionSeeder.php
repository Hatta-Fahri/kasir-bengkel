<?php

namespace Database\Seeders;

use App\Models\Prediction;
use App\Models\Sparepart;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PredictionSeeder extends Seeder
{
    /**
     * Seed data prediksi sparepart untuk testing fitur Prophet.
     *
     * Seeder ini mensimulasikan output nyata dari Python Facebook Prophet:
     * - Beberapa sparepart dengan estimasi kebutuhan berbeda-beda
     * - 3 bulan prediksi ke depan
     * - Beberapa dalam kondisi "perlu restock" (estimasi > stok), beberapa "aman"
     */
    public function run(): void
    {
        // Ambil sparepart yang ada di DB
        $spareparts = Sparepart::orderBy('id')->get();

        if ($spareparts->isEmpty()) {
            $this->command->warn('⚠  Tidak ada sparepart di database. Jalankan SparepartSeeder dulu.');
            return;
        }

        $versiModel     = 'v-prophet-test-' . now()->format('Ymd');
        $diGeneratePada = now();

        // 3 bulan prediksi: bulan ini, bulan depan, 2 bulan lagi
        $bulanPrediksi = [
            now()->startOfMonth()->toDateString(),
            now()->addMonth()->startOfMonth()->toDateString(),
            now()->addMonths(2)->startOfMonth()->toDateString(),
        ];

        $inserted = 0;

        foreach ($spareparts as $index => $sp) {
            // Buat pola estimasi yang bervariasi per sparepart
            // Agar ada yang "perlu restock" dan ada yang "aman"
            $baseEstimasi = match ($index % 4) {
                0 => $sp->stok * 1.5,  // butuh 150% dari stok → PERLU RESTOCK
                1 => $sp->stok * 0.6,  // butuh 60% dari stok  → AMAN
                2 => $sp->stok * 1.1,  // butuh 110% dari stok → PERLU RESTOCK (tipis)
                3 => $sp->stok * 0.3,  // butuh 30% dari stok  → SANGAT AMAN
                default => $sp->stok,
            };

            // Jika stok 0, beri estimasi tetap
            if ($sp->stok === 0) {
                $baseEstimasi = rand(10, 50);
            }

            foreach ($bulanPrediksi as $bulanIdx => $bulan) {
                // Variasi tren per bulan (naik/turun sedikit)
                $tren      = 1 + (($bulanIdx * 0.05) * ($index % 2 === 0 ? 1 : -1));
                $estimasi  = round(max(1, $baseEstimasi * $tren), 2);
                $margin    = round($estimasi * 0.15, 2); // ±15% confidence interval

                Prediction::updateOrCreate(
                    [
                        'sparepart_id'   => $sp->id,
                        'bulan_prediksi' => $bulan,
                    ],
                    [
                        'estimasi_kebutuhan' => $estimasi,
                        'batas_bawah'        => round(max(0, $estimasi - $margin), 2),
                        'batas_atas'         => round($estimasi + $margin, 2),
                        'versi_model'        => $versiModel,
                        'di_generate_pada'   => $diGeneratePada,
                    ]
                );

                $inserted++;
            }
        }

        $this->command->info("✅ PredictionSeeder selesai: {$inserted} baris prediksi dibuat");
        $this->command->info("   Sparepart: {$spareparts->count()} | Bulan: " . count($bulanPrediksi));
        $this->command->info("   Versi model: {$versiModel}");

        // Tampilkan ringkasan
        $perluRestock = Prediction::where('bulan_prediksi', $bulanPrediksi[0])
            ->get()
            ->filter(function ($p) {
                return (float) $p->estimasi_kebutuhan > ($p->sparepart?->stok ?? 0);
            })
            ->count();

        $this->command->table(
            ['Bulan', 'Prediksi Dibuat'],
            collect($bulanPrediksi)->map(fn ($b, $i) => [
                Carbon::parse($b)->locale('id')->translatedFormat('F Y'),
                $spareparts->count() . ' sparepart',
            ])->toArray()
        );

        $this->command->warn("   Bulan pertama: {$perluRestock} sparepart perlu restock (simulasi)");
    }
}
