@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page_title', 'Laporan Keuangan')

@section('content')
{{-- Chart.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<div class="space-y-5">

    {{-- ============================================================ --}}
    {{-- FILTER PERIODE                                               --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.reports.index') }}" id="form-filter">

            {{-- Tabs Periode --}}
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @foreach (['bulanan' => 'Bulanan', 'mingguan' => 'Mingguan', 'harian' => 'Harian'] as $val => $label)
                <a href="{{ route('admin.reports.index', array_merge(request()->except('periode'), ['periode' => $val])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                       {{ $periode === $val
                           ? 'bg-blue-900 text-white shadow'
                           : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Input kontrol per periode --}}
            <div class="flex flex-wrap gap-3 items-center">
                @if($periode === 'bulanan')
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Bulan</label>
                        <input type="month" name="bulan" value="{{ request('bulan', now()->format('Y-m')) }}"
                            class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                @elseif($periode === 'mingguan')
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Mulai Minggu (Senin)</label>
                        <input type="date" name="minggu" value="{{ request('minggu', now()->startOfWeek()->toDateString()) }}"
                            class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                @else
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', now()->toDateString()) }}"
                            class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                @endif
                <input type="hidden" name="periode" value="{{ $periode }}">
                <div class="mt-4">
                    <button type="submit" class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-colors">
                        Tampilkan
                    </button>
                </div>
            </div>

            {{-- Label periode aktif --}}
            <p class="text-xs text-gray-400 mt-3">
                Periode: <span class="font-semibold text-gray-600">
                    {{ $start->locale('id')->translatedFormat('d F Y') }} — {{ $end->locale('id')->translatedFormat('d F Y') }}
                </span>
            </p>
        </form>
    </div>

    {{-- ============================================================ --}}
    {{-- SUMMARY CARDS                                                --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Total Pendapatan</p>
            <p class="text-xl font-bold text-emerald-600 truncate">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $totalTransaksi }} transaksi</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Total Pengeluaran</p>
            <p class="text-xl font-bold text-red-500 truncate">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">biaya operasional</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border {{ $labaBersih >= 0 ? 'border-emerald-100' : 'border-red-100' }}">
            <p class="text-xs text-gray-400 mb-1">Laba Bersih</p>
            <p class="text-xl font-bold truncate {{ $labaBersih >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                {{ $labaBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
            </p>
            <p class="text-xs {{ $labaBersih >= 0 ? 'text-emerald-400' : 'text-red-400' }} mt-1">
                {{ $labaBersih >= 0 ? '▲ Untung' : '▼ Rugi' }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Jumlah Transaksi</p>
            <p class="text-xl font-bold text-blue-600">{{ number_format($totalTransaksi) }}</p>
            <p class="text-xs text-gray-400 mt-1">transaksi selesai</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- GRAFIK CHART.JS                                              --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <h3 class="text-sm font-bold text-gray-700">Grafik Pendapatan vs Pengeluaran</h3>
            <div class="flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Pendapatan</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-400 inline-block"></span>Pengeluaran</span>
            </div>
        </div>
        <div class="relative" style="height: 280px">
            <canvas id="chartKeuangan"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">

        {{-- ============================================================ --}}
        {{-- SPAREPART TERLARIS                                           --}}
        {{-- ============================================================ --}}
        @if($terlaris->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-700 mb-4">Sparepart Terlaris</h3>
            @php $maxQty = $terlaris->max('total_qty') ?: 1; @endphp
            <div class="space-y-3">
                @foreach($terlaris as $i => $item)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-medium text-gray-700 truncate flex-1">
                            <span class="text-gray-400 mr-1">{{ $i+1 }}.</span>
                            {{ $item->sparepart->nama_part ?? '—' }}
                        </span>
                        <span class="text-blue-600 font-bold ml-2 flex-shrink-0">{{ $item->total_qty }} {{ $item->sparepart->satuan ?? '' }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all"
                             style="width: {{ ($item->total_qty / $maxQty) * 100 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5 text-right">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-center">
            <p class="text-gray-400 text-sm">Tidak ada data penjualan sparepart pada periode ini.</p>
        </div>
        @endif

        {{-- ============================================================ --}}
        {{-- DAFTAR PENGELUARAN                                           --}}
        {{-- ============================================================ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-700">Rincian Pengeluaran</h3>
                <span class="text-xs text-gray-400">{{ $pengeluaran->count() }} item</span>
            </div>
            @if($pengeluaran->count() > 0)
            <div class="overflow-y-auto" style="max-height: 320px">
                <table class="w-full text-xs">
                    <thead class="sticky top-0 bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-2 text-gray-500 font-semibold">Tanggal</th>
                            <th class="text-left px-4 py-2 text-gray-500 font-semibold">Nama</th>
                            <th class="text-right px-4 py-2 text-gray-500 font-semibold">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pengeluaran as $exp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-gray-500 whitespace-nowrap">{{ $exp->tanggal_pengeluaran->format('d M') }}</td>
                            <td class="px-4 py-2">
                                <p class="text-gray-800 font-medium">{{ $exp->nama_pengeluaran }}</p>
                                @if($exp->kategori)
                                    <p class="text-gray-400">{{ $exp->kategori }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right font-semibold text-red-500 whitespace-nowrap">
                                Rp {{ number_format($exp->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-10 text-center text-gray-400 text-sm">Tidak ada pengeluaran pada periode ini.</div>
            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- TABEL TRANSAKSI                                              --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700">Riwayat Transaksi</h3>
            <span class="text-xs text-gray-400">{{ $transaksi->total() }} transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">No. Struk</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Kasir</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Tipe</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Total</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Metode</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transaksi as $trx)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs font-bold text-gray-700">{{ $trx->no_struk }}</td>
                        <td class="px-4 py-3 text-gray-600 text-sm">{{ $trx->kasir->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $trx->tipe_transaksi === 'servis' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($trx->tipe_transaksi) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium uppercase {{ $trx->metode_pembayaran === 'cash' ? 'text-gray-500' : 'text-purple-600' }}">
                                {{ $trx->metode_pembayaran }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                            {{ $trx->created_at->locale('id')->translatedFormat('d M Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-12 text-center text-gray-400">Tidak ada transaksi pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transaksi->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $transaksi->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
(function() {
    const labels = @json($labels);
    const pendapatan = @json($chartPendapatan);
    const pengeluaran = @json($chartPengeluaran);

    const ctx = document.getElementById('chartKeuangan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: pendapatan,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
                {
                    label: 'Pengeluaran',
                    data: pengeluaran,
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderColor: 'rgb(239, 68, 68)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleColor: '#94a3b8',
                    bodyColor: '#f1f5f9',
                    padding: 12,
                    callbacks: {
                        label: (ctx) => ' Rp ' + Math.round(ctx.raw).toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: 11 },
                        color: '#94a3b8',
                        maxRotation: 45,
                    }
                },
                y: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { size: 11 },
                        color: '#94a3b8',
                        callback: (v) => 'Rp ' + (v >= 1000000
                            ? (v/1000000).toFixed(1) + 'jt'
                            : v >= 1000 ? (v/1000).toFixed(0) + 'rb' : v),
                    }
                }
            }
        }
    });
})();
</script>
@endpush
@endsection
