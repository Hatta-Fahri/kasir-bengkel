<?php

namespace App\Console\Commands;

use App\Services\PredictionService;
use Illuminate\Console\Command;
use RuntimeException;

class GeneratePredictions extends Command
{
    protected $signature = 'prediction:generate
                            {--bulan=3 : Jumlah bulan ke depan yang diprediksi}';

    protected $description = 'Generate prediksi kebutuhan sparepart dengan memanggil prediction-service (FastAPI + Prophet)';

    public function handle(PredictionService $service): int
    {
        $bulanKeDepan = (int) $this->option('bulan');

        $this->info("Mengambil histori penjualan & memanggil prediction-service (bulan_ke_depan={$bulanKeDepan})...");

        try {
            $hasil = $service->generate($bulanKeDepan);
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        if (empty($hasil['hasil'])) {
            $this->warn('Tidak ada data histori penjualan yang bisa dikirim (transaksi status "selesai" kosong).');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->table(['Status', 'Jumlah'], [
            ['OK (disimpan ke predictions)', $hasil['ok']],
            ['Skipped (data historis kurang)', $hasil['skipped']],
            ['Error', $hasil['error']],
        ]);

        foreach ($hasil['hasil'] as $item) {
            if ($item['status'] !== 'ok') {
                $this->line("  - {$item['kode_part']}: {$item['status']} — " . ($item['alasan'] ?? '-'));
            }
        }

        $this->newLine();
        $this->info('Selesai. Lihat hasilnya di halaman Prediksi Sparepart pada dashboard admin.');

        return self::SUCCESS;
    }
}
