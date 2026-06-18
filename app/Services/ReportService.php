<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Bangun query range dari filter periode.
     * Mengembalikan [Carbon $start, Carbon $end, string $labelFormat, string $groupFormat]
     */
    private function buildRange(string $periode, ?string $bulan, ?string $minggu, ?string $tanggal, ?string $tahun): array
    {
        return match ($periode) {
            'harian' => [
                Carbon::parse($tanggal ?? now()->toDateString())->startOfDay(),
                Carbon::parse($tanggal ?? now()->toDateString())->endOfDay(),
                'H:00',      // label per jam
                '%H:00',     // mysql group format
            ],
            'mingguan' => [
                Carbon::parse($minggu ?? now()->startOfWeek()->toDateString())->startOfWeek()->startOfDay(),
                Carbon::parse($minggu ?? now()->startOfWeek()->toDateString())->endOfWeek()->endOfDay(),
                'd M',       // label per hari
                '%d %b',     // mysql group
            ],
            'tahunan' => [
                Carbon::parse(($tahun ?? now()->format('Y')) . '-01-01')->startOfYear()->startOfDay(),
                Carbon::parse(($tahun ?? now()->format('Y')) . '-01-01')->endOfYear()->endOfDay(),
                'M Y',       // label per bulan
                '%b %Y',     // mysql group
            ],
            default => [ // bulanan
                Carbon::parse(($bulan ?? now()->format('Y-m')) . '-01')->startOfMonth()->startOfDay(),
                Carbon::parse(($bulan ?? now()->format('Y-m')) . '-01')->endOfMonth()->endOfDay(),
                'd M',       // label per hari
                '%d %b',     // mysql group
            ],
        };
    }

    /**
     * Ambil semua data laporan berdasarkan filter periode.
     * $export = true akan mengambil seluruh transaksi tanpa paginasi (untuk cetak laporan).
     */
    public function getLaporan(string $periode, ?string $bulan, ?string $minggu, ?string $tanggal, ?string $tahun = null, bool $export = false): array
    {
        [$start, $end, $labelFmt, $mysqlFmt] = $this->buildRange($periode, $bulan, $minggu, $tanggal, $tahun);

        // ── Pendapatan per periode ──
        $pendapatanRaw = Transaction::select(
                DB::raw("DATE_FORMAT(created_at, '{$mysqlFmt}') as label"),
                DB::raw('SUM(total_bayar) as total')
            )
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        // ── Pengeluaran per periode ──
        $pengeluaranRaw = Expense::select(
                DB::raw("DATE_FORMAT(tanggal_pengeluaran, '{$mysqlFmt}') as label"),
                DB::raw('SUM(jumlah) as total')
            )
            ->whereBetween('tanggal_pengeluaran', [$start->toDateString(), $end->toDateString()])
            ->groupBy('label')
            ->orderBy('label')
            ->pluck('total', 'label');

        // ── Bangun labels lengkap ──
        $labels = $this->buildLabels($periode, $start, $end);

        $chartPendapatan = [];
        $chartPengeluaran = [];
        foreach ($labels as $label) {
            $chartPendapatan[]  = (float) ($pendapatanRaw[$label] ?? 0);
            $chartPengeluaran[] = (float) ($pengeluaranRaw[$label] ?? 0);
        }

        // ── Summary Totals ──
        $totalPendapatan  = Transaction::where('status', 'selesai')->whereBetween('created_at', [$start, $end])->sum('total_bayar');
        $totalPengeluaran = Expense::whereBetween('tanggal_pengeluaran', [$start->toDateString(), $end->toDateString()])->sum('jumlah');
        $labaBersih       = $totalPendapatan - $totalPengeluaran;
        $totalTransaksi   = Transaction::where('status', 'selesai')->whereBetween('created_at', [$start, $end])->count();

        // ── Tabel transaksi ──
        $transaksiQuery = Transaction::with('kasir')
            ->where('status', 'selesai')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at');

        $transaksi = $export
            ? $transaksiQuery->get()
            : $transaksiQuery->paginate(15)->withQueryString();

        // ── Pengeluaran list ──
        $pengeluaran = Expense::with('admin')
            ->whereBetween('tanggal_pengeluaran', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('tanggal_pengeluaran')
            ->get();

        // ── Sparepart terlaris periode ini ──
        $terlaris = TransactionDetail::select(
                'sparepart_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(subtotal) as total_pendapatan')
            )
            ->with('sparepart:id,nama_part,satuan')
            ->whereHas('transaction', fn ($q) =>
                $q->where('status', 'selesai')->whereBetween('created_at', [$start, $end])
            )
            ->groupBy('sparepart_id')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        return compact(
            'labels', 'chartPendapatan', 'chartPengeluaran',
            'totalPendapatan', 'totalPengeluaran', 'labaBersih', 'totalTransaksi',
            'transaksi', 'pengeluaran', 'terlaris',
            'start', 'end', 'periode'
        );
    }

    /**
     * Generate array label sesuai periode.
     */
    private function buildLabels(string $periode, Carbon $start, Carbon $end): array
    {
        $labels = [];

        if ($periode === 'harian') {
            for ($h = 0; $h <= 23; $h++) {
                $labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            }
        } elseif ($periode === 'tahunan') {
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $labels[] = $cursor->format('M Y');
                $cursor->addMonth();
            }
        } else {
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $labels[] = $cursor->format('d M');
                $cursor->addDay();
            }
        }

        return $labels;
    }
}
