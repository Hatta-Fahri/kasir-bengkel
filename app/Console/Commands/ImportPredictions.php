<?php

namespace App\Console\Commands;

use App\Models\Prediction;
use App\Models\Sparepart;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ImportPredictions extends Command
{
    /**
     * Format: php artisan prediction:import /path/to/predictions.json [--versi=v1.0]
     *
     * Format JSON yang diterima:
     * [
     *   {
     *     "sparepart_id": 1,
     *     "bulan_prediksi": "2026-07-01",
     *     "estimasi_kebutuhan": 45.5,
     *     "batas_bawah": 38.2,
     *     "batas_atas": 52.8,
     *     "di_generate_pada": "2026-05-17T08:00:00"
     *   },
     *   ...
     * ]
     *
     * Atau dengan kode_part (lebih fleksibel):
     * [
     *   {
     *     "kode_part": "SP-0001",
     *     "bulan_prediksi": "2026-07-01",
     *     "estimasi_kebutuhan": 45.5,
     *     ...
     *   }
     * ]
     */
    protected $signature = 'prediction:import
                            {file : Path ke file JSON hasil output Prophet}
                            {--versi= : Versi/run-ID model (opsional, default: tanggal hari ini)}
                            {--dry-run : Tampilkan preview tanpa menyimpan ke database}';

    protected $description = 'Import hasil prediksi sparepart dari output Python Prophet (JSON) ke database';

    public function handle(): int
    {
        $filePath = $this->argument('file');
        $versi    = $this->option('versi') ?? 'v-' . now()->format('Ymd-His');
        $dryRun   = $this->option('dry-run');

        // ── Validasi file ──
        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            return self::FAILURE;
        }

        $raw = file_get_contents($filePath);
        $rows = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('File bukan JSON yang valid: ' . json_last_error_msg());
            return self::FAILURE;
        }

        if (!is_array($rows) || count($rows) === 0) {
            $this->warn('File JSON kosong atau tidak berisi array.');
            return self::SUCCESS;
        }

        $this->info("📂 File: {$filePath}");
        $this->info("🔖 Versi Model: {$versi}");
        $this->info("📊 Total baris: " . count($rows));
        $this->newLine();

        if ($dryRun) {
            $this->warn('⚠  Mode DRY-RUN — tidak ada data yang disimpan.');
        }

        // ── Proses import ──
        $inserted  = 0;
        $updated   = 0;
        $skipped   = 0;
        $errors    = 0;

        $tableRows = [];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                // Resolve sparepart_id
                $sparepartId = null;
                if (!empty($row['sparepart_id'])) {
                    $sparepartId = (int) $row['sparepart_id'];
                } elseif (!empty($row['kode_part'])) {
                    $sp = Sparepart::withTrashed()->where('kode_part', $row['kode_part'])->first();
                    $sparepartId = $sp?->id;
                }

                if (!$sparepartId) {
                    $this->warn("  [baris {$i}] Sparepart tidak ditemukan. Lewati.");
                    $skipped++;
                    continue;
                }

                // Validasi field wajib
                if (empty($row['bulan_prediksi']) || empty($row['estimasi_kebutuhan'])) {
                    $this->warn("  [baris {$i}] Field bulan_prediksi / estimasi_kebutuhan kosong. Lewati.");
                    $skipped++;
                    continue;
                }

                $bulan = Carbon::parse($row['bulan_prediksi'])->startOfMonth()->toDateString();
                $sparepart = Sparepart::withTrashed()->find($sparepartId);

                $payload = [
                    'sparepart_id'       => $sparepartId,
                    'bulan_prediksi'     => $bulan,
                    'estimasi_kebutuhan' => round((float) $row['estimasi_kebutuhan'], 2),
                    'batas_bawah'        => isset($row['batas_bawah'])  ? round((float) $row['batas_bawah'], 2)  : null,
                    'batas_atas'         => isset($row['batas_atas'])   ? round((float) $row['batas_atas'], 2)   : null,
                    'versi_model'        => $versi,
                    'di_generate_pada'   => isset($row['di_generate_pada'])
                        ? Carbon::parse($row['di_generate_pada'])
                        : now(),
                ];

                $tableRows[] = [
                    $sparepart->nama_part ?? $sparepartId,
                    $bulan,
                    number_format($payload['estimasi_kebutuhan'], 1),
                    number_format($payload['batas_bawah'] ?? 0, 1) . ' – ' . number_format($payload['batas_atas'] ?? 0, 1),
                ];

                if (!$dryRun) {
                    $exists = Prediction::where('sparepart_id', $sparepartId)
                        ->where('bulan_prediksi', $bulan)
                        ->exists();

                    Prediction::updateOrCreate(
                        ['sparepart_id' => $sparepartId, 'bulan_prediksi' => $bulan],
                        $payload
                    );

                    $exists ? $updated++ : $inserted++;
                } else {
                    $inserted++;
                }
            }

            if (!$dryRun) {
                DB::commit();
            } else {
                DB::rollBack();
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import gagal: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ── Tampilkan tabel preview ──
        $this->table(
            ['Sparepart', 'Bulan Prediksi', 'Estimasi', 'Confidence Interval'],
            $tableRows
        );

        $this->newLine();
        if ($dryRun) {
            $this->info("🔍 Dry run selesai. {$inserted} baris siap diimport.");
        } else {
            $this->info("✅ Import selesai!");
            $this->table(['Status', 'Jumlah'], [
                ['✅ Inserted (baru)', $inserted],
                ['🔄 Updated (timpa)', $updated],
                ['⚠  Dilewati',        $skipped],
            ]);
        }

        return self::SUCCESS;
    }
}
