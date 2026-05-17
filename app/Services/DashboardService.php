<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    // =========================================================================
    // ADMIN STATS
    // =========================================================================

    /**
     * Semua statistik untuk dashboard Admin bulan ini.
     */
    public function adminStats(): array
    {
        $bulanIni  = now()->startOfMonth();
        $bulanAkhir = now()->endOfMonth();

        $totalPendapatan = Transaction::whereBetween('created_at', [$bulanIni, $bulanAkhir])
            ->where('status', 'selesai')
            ->sum('total_bayar');

        $totalTransaksi = Transaction::whereBetween('created_at', [$bulanIni, $bulanAkhir])
            ->where('status', 'selesai')
            ->count();

        $totalPengeluaran = Expense::whereBetween('tanggal_pengeluaran', [
            $bulanIni->toDateString(),
            $bulanAkhir->toDateString(),
        ])->sum('jumlah');

        $labaBersih = $totalPendapatan - $totalPengeluaran;

        $stokMenipis = Sparepart::stokMenipis()->count();

        // Pendapatan 7 hari terakhir untuk mini-chart
        $pendapatan7Hari = Transaction::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->where('status', 'selesai')
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');

        // Isi hari yang tidak ada transaksi dengan 0
        $chart = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i)->toDateString();
            $chart[] = [
                'label' => now()->subDays($i)->locale('id')->translatedFormat('D d/m'),
                'total' => (float) ($pendapatan7Hari[$tgl]->total ?? 0),
            ];
        }

        return compact(
            'totalPendapatan',
            'totalTransaksi',
            'totalPengeluaran',
            'labaBersih',
            'stokMenipis',
            'chart'
        );
    }

    /**
     * Sparepart terlaris bulan ini (top 5 berdasarkan qty terjual).
     */
    public function sparepartTerlaris(int $limit = 5): \Illuminate\Support\Collection
    {
        return TransactionDetail::select('sparepart_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_pendapatan'))
            ->with('sparepart:id,nama_part,satuan')
            ->whereHas('transaction', fn ($q) => $q->where('status', 'selesai')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
            )
            ->groupBy('sparepart_id')
            ->orderByDesc('total_qty')
            ->limit($limit)
            ->get();
    }

    /**
     * Stok menipis untuk notifikasi sidebar.
     */
    public function stokMenipisList(int $limit = 5): \Illuminate\Support\Collection
    {
        return Sparepart::stokMenipis()
            ->select('id', 'nama_part', 'stok', 'stok_minimum', 'satuan')
            ->orderBy('stok')
            ->limit($limit)
            ->get();
    }

    // =========================================================================
    // KASIR STATS
    // =========================================================================

    /**
     * Statistik untuk dashboard Kasir (hari ini saja).
     */
    public function kasirStats(int $kasirId): array
    {
        $today = now()->toDateString();

        $transaksiHariIni = Transaction::where('kasir_id', $kasirId)
            ->where('status', 'selesai')
            ->whereDate('created_at', $today)
            ->count();

        $pendapatanHariIni = Transaction::where('kasir_id', $kasirId)
            ->where('status', 'selesai')
            ->whereDate('created_at', $today)
            ->sum('total_bayar');

        $transaksiMingguIni = Transaction::where('kasir_id', $kasirId)
            ->where('status', 'selesai')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $transaksiTerakhir = Transaction::where('kasir_id', $kasirId)
            ->where('status', 'selesai')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'no_struk', 'tipe_transaksi', 'total_bayar', 'created_at']);

        return compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiMingguIni',
            'transaksiTerakhir'
        );
    }
}
