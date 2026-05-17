@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6 text-slate-800">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Transaksi Saya</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Semua transaksi yang telah Anda kerjakan</p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Transaksi Baru
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5">
        <form method="GET" action="{{ route('kasir.transactions.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="pl-4 pr-4 py-3 text-sm bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 text-slate-700 font-medium transition-shadow">
            </div>
            <div class="relative">
                <select name="tipe" class="pl-4 pr-10 py-3 text-sm bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 text-slate-700 font-medium transition-shadow appearance-none">
                    <option value="">Semua Tipe Transaksi</option>
                    <option value="penjualan" {{ request('tipe') === 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                    <option value="servis" {{ request('tipe') === 'servis' ? 'selected' : '' }}>Servis</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-slate-900/10">Filter Data</button>
            
            @if(request('tanggal') || request('tipe'))
                <a href="{{ route('kasir.transactions.index') }}" class="px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">No. Struk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Info Kendaraan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">Total Bayar</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Metode</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        {{-- No Struk --}}
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                            {{ $trx->no_struk }}
                        </td>
                        
                        {{-- Tipe --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($trx->tipe_transaksi === 'servis')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-700 shadow-sm">
                                    Servis
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white shadow-sm">
                                    Penjualan
                                </span>
                            @endif
                        </td>
                        
                        {{-- Info Kendaraan --}}
                        <td class="px-6 py-4">
                            @if($trx->tipe_transaksi === 'servis')
                                <p class="font-bold text-slate-900">{{ $trx->jenis_mobil }}</p>
                                <p class="font-mono text-xs text-slate-500 font-medium mt-0.5">{{ strtoupper($trx->plat_nomor) }}</p>
                            @else
                                <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        
                        {{-- Total Bayar --}}
                        <td class="px-6 py-4 text-right font-bold text-slate-900 tracking-tight whitespace-nowrap">
                            Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                        </td>
                        
                        {{-- Metode Pembayaran --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-600">
                                {{ $trx->metode_pembayaran }}
                            </span>
                        </td>
                        
                        {{-- Waktu --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900">{{ $trx->created_at->locale('id')->translatedFormat('d M Y') }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('kasir.transactions.receipt', $trx->id) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-slate-400 hover:bg-slate-50 rounded-xl transition-all shadow-sm whitespace-nowrap">
                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .121.08.232.198.256 3.425.688 6.945.688 10.404 0 .118-.024.198-.135.198-.256V7.03z" /></svg>
                                Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada transaksi ditemukan</p>
                            <a href="{{ route('kasir.transactions.create') }}" class="text-slate-900 text-sm font-bold hover:underline mt-2 inline-block">Mulai transaksi baru &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection