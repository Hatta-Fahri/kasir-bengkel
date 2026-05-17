@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Riwayat Transaksi Saya</h1>
            <p class="text-sm text-gray-400 mt-0.5">Semua transaksi yang Anda kerjakan</p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow shadow-amber-500/30 transition-all active:scale-95">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            Transaksi Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('kasir.transactions.index') }}" class="flex flex-wrap gap-3 items-center">
            <div>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <div>
                <select name="tipe" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                    <option value="">Semua Tipe</option>
                    <option value="penjualan" {{ request('tipe') === 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                    <option value="servis" {{ request('tipe') === 'servis' ? 'selected' : '' }}>Servis</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
            @if(request('tanggal') || request('tipe'))
                <a href="{{ route('kasir.transactions.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">No. Struk</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Tipe</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Info Kendaraan</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Total Bayar</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Metode</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Waktu</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono font-bold text-gray-700 whitespace-nowrap">{{ $trx->no_struk }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                {{ $trx->tipe_transaksi === 'servis' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($trx->tipe_transaksi) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            @if($trx->tipe_transaksi === 'servis')
                                <p class="font-medium text-gray-800">{{ $trx->jenis_mobil }}</p>
                                <p class="font-mono text-xs text-gray-400">{{ strtoupper($trx->plat_nomor) }}</p>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                {{ $trx->metode_pembayaran === 'cash' ? 'bg-gray-100 text-gray-600' : 'bg-purple-100 text-purple-600' }}">
                                {{ strtoupper($trx->metode_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap text-xs">
                            {{ $trx->created_at->locale('id')->translatedFormat('d M Y') }}<br>
                            <span class="text-gray-300">{{ $trx->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('kasir.transactions.receipt', $trx->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153C17.99 6.924 19 8.091 19 9.473v5.277A2.25 2.25 0 0 1 16.75 17h-.5v.25A1.75 1.75 0 0 1 14.5 19h-9a1.75 1.75 0 0 1-1.75-1.75V17h-.5A2.25 2.25 0 0 1 1 14.75V9.473c0-1.382 1.01-2.549 2.374-2.768.374-.056.75-.107 1.126-.153V2.75Zm1.5 0v3.301l6 .003V2.75a.25.25 0 0 0-.25-.25h-5.5a.25.25 0 0 0-.25.25Z" clip-rule="evenodd"/></svg>
                                Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <p class="text-gray-400 font-medium">Belum ada transaksi</p>
                            <a href="{{ route('kasir.transactions.create') }}" class="text-sm text-amber-500 hover:underline mt-1 inline-block">Mulai transaksi baru</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
