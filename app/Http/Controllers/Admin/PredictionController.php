<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prediction;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PredictionController extends Controller
{
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

        // Enrichment: bandingkan dengan stok saat ini untuk rekomendasi restok
        $predictions = $predictions->map(function (Prediction $p) {
            $stokSaatIni   = $p->sparepart?->stok ?? 0;
            $estimasi      = (float) $p->estimasi_kebutuhan;
            $selisih       = $estimasi - $stokSaatIni;
            $perluRestok   = $selisih > 0;
            $jumlahRestok  = $perluRestok ? ceil($selisih) : 0;

            $p->stok_saat_ini  = $stokSaatIni;
            $p->selisih        = $selisih;
            $p->perlu_restok   = $perluRestok;
            $p->jumlah_restok  = $jumlahRestok;

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

        return view('admin.predictions.index', compact(
            'predictions',
            'bulan',
            'bulanTersedia',
            'spareparts',
            'totalPerluRestok',
            'totalCukup',
            'versiModel',
            'diGeneratePada',
        ));
    }
}
