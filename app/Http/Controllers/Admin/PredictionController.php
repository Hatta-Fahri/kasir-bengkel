<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\Sparepart;
use App\Services\PredictionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use RuntimeException;

class PredictionController extends Controller
{
    /**
     * Horizon forecasting tetap 1 tahun penuh setiap generate — cukup untuk
     * planning tahunan bengkel dan mengaktifkan yearly_seasonality di Prophet.
     * Bulan mana yang ingin dilihat diatur lewat filter "Bulan Prediksi", bukan di sini.
     */
    private const BULAN_KE_DEPAN = 12;

    /**
     * Tampilkan halaman prediksi sparepart.
     * Filter: sparepart tertentu atau semua, bulan tertentu atau 3 bulan ke depan.
     */
    public function index(Request $request): View
    {
        // Default: prediksi bulan depan dan seterusnya
        $bulan = $request->filled('bulan')
            ? Carbon::parse($request->bulan . '-01')->startOfMonth()
            : now()->startOfMonth();

        // Query prediksi bulan yang dipilih
        $predictions = Prediction::with('sparepart')
            ->when($request->filled('sparepart_id'), fn ($q) =>
                $q->where('sparepart_id', $request->sparepart_id)
            )
            ->whereMonth('bulan_prediksi', $bulan->month)
            ->whereYear('bulan_prediksi', $bulan->year)
            ->orderByDesc('estimasi_kebutuhan')
            ->get();

        // Total kebutuhan KUMULATIF dari bulan ini s.d. bulan yang dipilih, per sparepart.
        // Penting: stok saat ini akan terpakai duluan di bulan-bulan sebelumnya, jadi
        // status "Aman/Restock" untuk bulan ke-N harus dibandingkan dengan total kebutuhan
        // bulan ke-1..ke-N (asumsi tidak ada pembelian/restock di antaranya) — bukan cuma
        // kebutuhan bulan ke-N saja.
        $kumulatifPerSparepart = Prediction::query()
            ->whereIn('sparepart_id', $predictions->pluck('sparepart_id'))
            ->where('bulan_prediksi', '>=', now()->startOfMonth())
            ->where('bulan_prediksi', '<=', $bulan)
            ->selectRaw('sparepart_id, SUM(estimasi_kebutuhan) as total')
            ->groupBy('sparepart_id')
            ->pluck('total', 'sparepart_id');

        // Enrichment: bandingkan kebutuhan kumulatif dengan stok saat ini untuk rekomendasi restok
        $predictions = $predictions->map(function (Prediction $p) use ($kumulatifPerSparepart) {
            $stokSaatIni        = $p->sparepart?->stok ?? 0;
            $kebutuhanKumulatif = (float) ($kumulatifPerSparepart[$p->sparepart_id] ?? $p->estimasi_kebutuhan);
            $selisih            = $kebutuhanKumulatif - $stokSaatIni;
            $perluRestok        = $selisih > 0;
            $jumlahRestok       = $perluRestok ? ceil($selisih) : 0;

            $p->stok_saat_ini         = $stokSaatIni;
            $p->kebutuhan_kumulatif   = $kebutuhanKumulatif;
            $p->selisih               = $selisih;
            $p->perlu_restok          = $perluRestok;
            $p->jumlah_restok         = $jumlahRestok;

            return $p;
        });

        // Statistik ringkasan
        $totalPerluRestok = $predictions->where('perlu_restok', true)->count();
        $totalCukup       = $predictions->where('perlu_restok', false)->count();
        $versiModel       = $predictions->first()?->versi_model ?? '—';
        $diGeneratePada   = $predictions->first()?->di_generate_pada;

        // List bulan yang tersedia di DB (untuk dropdown navigasi)
        $bulanTersedia = Prediction::selectRaw('DATE_FORMAT(bulan_prediksi, "%Y-%m") as bulan_key, DATE_FORMAT(bulan_prediksi, "%M %Y") as bulan_label')
            ->groupBy('bulan_key', 'bulan_label')
            ->orderBy('bulan_key')
            ->get();

        // Dropdown sparepart
        $spareparts = Sparepart::orderBy('nama_part')->get(['id', 'nama_part', 'kode_part']);

        // Untuk bedakan empty-state: belum pernah generate sama sekali,
        // vs sudah ada data tapi tidak match dengan filter yang sedang dipilih.
        $adaPrediksiSamaSekali = Prediction::query()->exists();

        return view('admin.predictions.index', compact(
            'predictions',
            'bulan',
            'bulanTersedia',
            'spareparts',
            'totalPerluRestok',
            'totalCukup',
            'versiModel',
            'diGeneratePada',
            'adaPrediksiSamaSekali',
        ));
    }

    /**
     * Trigger generate prediksi: ambil histori penjualan, kirim ke prediction-service
     * (FastAPI + Prophet), simpan hasilnya ke tabel predictions.
     */
    public function generate(PredictionService $service): RedirectResponse
    {
        try {
            $hasil = $service->generate(self::BULAN_KE_DEPAN);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        if (empty($hasil['hasil'])) {
            return back()->with('error', 'Tidak ada data histori penjualan yang bisa diprediksi (belum ada transaksi berstatus "selesai").');
        }

        $pesan = "Prediksi berhasil digenerate: {$hasil['ok']} sparepart OK, {$hasil['skipped']} dilewati (data kurang), {$hasil['error']} error.";

        // Arahkan ke bulan prediksi paling awal yang baru dibuat, supaya hasilnya
        // langsung terlihat tanpa perlu ganti filter "Bulan Prediksi" manual.
        $bulanPertama = collect($hasil['hasil'])
            ->where('status', 'ok')
            ->pluck('prediksi')
            ->flatten(1)
            ->pluck('bulan_prediksi')
            ->min();

        if (!$bulanPertama) {
            return back()->with('success', $pesan);
        }

        return redirect()
            ->route('admin.predictions.index', ['bulan' => substr($bulanPertama, 0, 7)])
            ->with('success', $pesan);
    }
}
