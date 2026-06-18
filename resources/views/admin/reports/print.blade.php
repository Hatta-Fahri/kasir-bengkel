<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan {{ ucfirst($periode) }} — Kasir Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4;
                margin: 14mm;
            }
        }
    </style>
</head>

<body class="bg-white text-gray-800 text-sm">
    <div class="max-w-4xl mx-auto p-6">

        {{-- Action buttons --}}
        <div class="no-print flex items-center justify-end gap-2 mb-4">
            <button onclick="window.close()"
                class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                Tutup
            </button>
            <button onclick="window.print()"
                class="px-4 py-2 text-sm font-semibold text-white bg-blue-900 hover:bg-blue-800 rounded-xl transition-colors">
                Cetak
            </button>
        </div>

        {{-- Header --}}
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-5">
            <h1 class="text-xl font-bold tracking-wide">BENGKEL BOS AIR CONDITIONER</h1>
            <p class="text-gray-500 text-xs">Sistem Kasir Bengkel</p>
            <h2 class="text-lg font-semibold mt-3">LAPORAN KEUANGAN {{ strtoupper($periode) }}</h2>
            <p class="text-gray-600 text-xs mt-1">
                Periode: {{ $start->locale('id')->translatedFormat('d F Y') }} —
                {{ $end->locale('id')->translatedFormat('d F Y') }}
            </p>
        </div>

        {{-- Summary --}}
        <div class="grid grid-cols-4 gap-3 mb-6 text-center">
            <div class="border border-gray-300 rounded-lg p-3">
                <p class="text-[11px] text-gray-500">Total Pendapatan</p>
                <p class="font-bold text-emerald-700">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="border border-gray-300 rounded-lg p-3">
                <p class="text-[11px] text-gray-500">Total Pengeluaran</p>
                <p class="font-bold text-red-700">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
            </div>
            <div class="border border-gray-300 rounded-lg p-3">
                <p class="text-[11px] text-gray-500">Laba Bersih</p>
                <p class="font-bold {{ $labaBersih >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                    {{ $labaBersih < 0 ? '-' : '' }}Rp {{ number_format(abs($labaBersih), 0, ',', '.') }}
                </p>
            </div>
            <div class="border border-gray-300 rounded-lg p-3">
                <p class="text-[11px] text-gray-500">Jumlah Transaksi</p>
                <p class="font-bold text-blue-700">{{ number_format($totalTransaksi) }}</p>
            </div>
        </div>

        {{-- Tabel Transaksi --}}
        <h3 class="font-bold text-sm mb-2">Riwayat Transaksi ({{ $transaksi->count() }})</h3>
        <table class="w-full text-xs border border-gray-300 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-2 py-1.5 text-left">No. Struk</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Kasir</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Tipe</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-right">Total</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Metode</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $trx)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1 font-mono">{{ $trx->no_struk }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $trx->kasir->name ?? '—' }}</td>
                        <td class="border border-gray-300 px-2 py-1 capitalize">{{ $trx->tipe_transaksi }}</td>
                        <td class="border border-gray-300 px-2 py-1 text-right">Rp
                            {{ number_format($trx->total_bayar, 0, ',', '.') }}</td>
                        <td class="border border-gray-300 px-2 py-1 uppercase">{{ $trx->metode_pembayaran }}</td>
                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                            {{ $trx->created_at->locale('id')->translatedFormat('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-2 py-3 text-center text-gray-400">Tidak ada
                            transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($transaksi->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="border border-gray-300 px-2 py-1.5 text-right">Total</td>
                        <td class="border border-gray-300 px-2 py-1.5 text-right">Rp
                            {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        <td colspan="2" class="border border-gray-300"></td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- Tabel Pengeluaran --}}
        <h3 class="font-bold text-sm mb-2">Rincian Pengeluaran ({{ $pengeluaran->count() }})</h3>
        <table class="w-full text-xs border border-gray-300 mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Tanggal</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Nama</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-left">Kategori</th>
                    <th class="border border-gray-300 px-2 py-1.5 text-right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengeluaran as $exp)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $exp->tanggal_pengeluaran->format('d/m/Y') }}
                        </td>
                        <td class="border border-gray-300 px-2 py-1">{{ $exp->nama_pengeluaran }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $exp->kategori ?? '—' }}</td>
                        <td class="border border-gray-300 px-2 py-1 text-right">Rp
                            {{ number_format($exp->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="border border-gray-300 px-2 py-3 text-center text-gray-400">Tidak ada
                            pengeluaran pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($pengeluaran->count() > 0)
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="border border-gray-300 px-2 py-1.5 text-right">Total</td>
                        <td class="border border-gray-300 px-2 py-1.5 text-right">Rp
                            {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>

        {{-- Sparepart Terlaris --}}
        @if ($terlaris->count() > 0)
            <h3 class="font-bold text-sm mb-2">Sparepart Terlaris</h3>
            <table class="w-full text-xs border border-gray-300 mb-6">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-2 py-1.5 text-left">#</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-left">Nama Sparepart</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-right">Qty Terjual</th>
                        <th class="border border-gray-300 px-2 py-1.5 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($terlaris as $i => $item)
                        <tr>
                            <td class="border border-gray-300 px-2 py-1">{{ $i + 1 }}</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $item->sparepart->nama_part ?? '—' }}</td>
                            <td class="border border-gray-300 px-2 py-1 text-right">{{ $item->total_qty }}
                                {{ $item->sparepart->satuan ?? '' }}</td>
                            <td class="border border-gray-300 px-2 py-1 text-right">Rp
                                {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- Footer --}}
        <div class="flex justify-between items-end mt-12 pt-4 text-xs text-gray-500">
            <p>
                Dicetak oleh: {{ $printedBy }}<br>
                Pada: {{ $printedAt->locale('id')->translatedFormat('d F Y, H:i') }}
            </p>
            <div class="text-center">
                <p class="mb-16">Mengetahui,</p>
                <p class="border-t border-gray-400 pt-1 px-6">{{ $printedBy }}</p>
            </div>
        </div>
    </div>
</body>

</html>
