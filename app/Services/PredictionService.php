<?php

namespace App\Services;

use App\Models\Prediction;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PredictionService
{
    /**
     * Ambil histori penjualan bulanan per sparepart, kirim ke prediction-service
     * (FastAPI + Prophet), lalu simpan hasil yang berstatus "ok" ke tabel predictions.
     *
     * @return array{ok: int, skipped: int, error: int, hasil: array}
     */
    public function generate(int $bulanKeDepan = 3): array
    {
        [$payload, $kodeKeSparepartId] = $this->buildPayload($bulanKeDepan);

        if (empty($payload['data'])) {
            return ['ok' => 0, 'skipped' => 0, 'error' => 0, 'hasil' => []];
        }

        $response = $this->callPredictApi($payload);

        $versiModel = $response['versi_model'] ?? null;
        $diGeneratePada = isset($response['di_generate_pada'])
            ? Carbon::parse($response['di_generate_pada'])
            : now();

        $counts = ['ok' => 0, 'skipped' => 0, 'error' => 0];

        foreach ($response['hasil'] ?? [] as $item) {
            $counts[$item['status']] = ($counts[$item['status']] ?? 0) + 1;

            if ($item['status'] !== 'ok') {
                continue;
            }

            $sparepartId = $kodeKeSparepartId[$item['kode_part']] ?? null;
            if (!$sparepartId) {
                continue;
            }

            foreach ($item['prediksi'] as $p) {
                Prediction::updateOrCreate(
                    [
                        'sparepart_id' => $sparepartId,
                        'bulan_prediksi' => $p['bulan_prediksi'],
                    ],
                    [
                        'estimasi_kebutuhan' => $p['estimasi_kebutuhan'],
                        'batas_bawah' => $p['batas_bawah'],
                        'batas_atas' => $p['batas_atas'],
                        'versi_model' => $versiModel,
                        'di_generate_pada' => $diGeneratePada,
                    ]
                );
            }

            // Bersihkan sisa prediksi lama yang melebihi horizon permintaan generate
            // ini (misalnya sisa generate sebelumnya dengan bulan_ke_depan yang lebih
            // besar), supaya data yang tersimpan selalu konsisten dengan generate terakhir.
            $bulanTerjauh = collect($item['prediksi'])->pluck('bulan_prediksi')->max();
            Prediction::where('sparepart_id', $sparepartId)
                ->where('bulan_prediksi', '>', $bulanTerjauh)
                ->delete();
        }

        return $counts + ['hasil' => $response['hasil'] ?? []];
    }

    /**
     * Susun payload /predict: qty terjual per bulan per sparepart, hanya dari
     * transaksi berstatus "selesai". Histori diagregasi di sisi Laravel supaya
     * prediction-service tetap stateless dan payload tetap ringkas.
     *
     * @return array{0: array, 1: array<string, int>}
     */
    private function buildPayload(int $bulanKeDepan): array
    {
        $rows = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('spareparts', 'spareparts.id', '=', 'transaction_details.sparepart_id')
            ->where('transactions.status', 'selesai')
            ->whereNull('spareparts.deleted_at')
            ->selectRaw(
                "spareparts.id as sparepart_id, spareparts.kode_part, spareparts.nama_part,
                 DATE_FORMAT(transactions.created_at, '%Y-%m-01') as bulan,
                 SUM(transaction_details.qty) as qty"
            )
            ->groupBy('spareparts.id', 'spareparts.kode_part', 'spareparts.nama_part', 'bulan')
            ->orderBy('bulan')
            ->get();

        $perSparepart = [];
        $kodeKeSparepartId = [];

        foreach ($rows as $row) {
            $kodeKeSparepartId[$row->kode_part] = $row->sparepart_id;

            $perSparepart[$row->kode_part] ??= [
                'kode_part' => $row->kode_part,
                'nama_part' => $row->nama_part,
                'histori' => [],
            ];

            $perSparepart[$row->kode_part]['histori'][] = [
                'bulan' => $row->bulan,
                'qty' => (int) $row->qty,
            ];
        }

        $payload = [
            'bulan_ke_depan' => $bulanKeDepan,
            'data' => array_values($perSparepart),
        ];

        return [$payload, $kodeKeSparepartId];
    }

    /**
     * @throws RuntimeException jika prediction-service tidak bisa dihubungi atau merespons error
     */
    private function callPredictApi(array $payload): array
    {
        $baseUrl = rtrim(config('services.prediction_service.base_url'), '/');
        $apiKey = config('services.prediction_service.api_key');
        $timeout = config('services.prediction_service.timeout', 30);

        Log::info('[prediction-service] REQUEST POST /predict', ['payload' => $payload]);

        try {
            $response = Http::withHeaders(array_filter([
                    'X-API-Key' => $apiKey,
                ]))
                ->timeout($timeout)
                ->post("{$baseUrl}/predict", $payload);
        } catch (ConnectionException $e) {
            Log::error('[prediction-service] Gagal terhubung', ['base_url' => $baseUrl]);
            throw new RuntimeException(
                "Tidak bisa menghubungi prediction-service di {$baseUrl}. Pastikan service Python (uvicorn) sedang berjalan.",
                previous: $e
            );
        }

        Log::info('[prediction-service] RESPONSE', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                "Prediction-service merespons error ({$response->status()}): {$response->body()}"
            );
        }

        return $response->json();
    }
}
