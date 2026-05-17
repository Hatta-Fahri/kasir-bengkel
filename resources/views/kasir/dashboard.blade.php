@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6 text-slate-800">

    {{-- Greeting Banner --}}
    <div class="relative overflow-hidden rounded-3xl p-8 shadow-xl shadow-slate-900/10 bg-slate-900">
        {{-- Decorative Background --}}
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
        
        <div class="relative flex items-center justify-between flex-wrap gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-sm font-medium tracking-wide">Selamat datang kembali</p>
                    <h1 class="text-white text-2xl font-bold mt-1 tracking-tight">{{ auth()->user()->name }}</h1>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-white/10 border border-white/20 px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                            Kasir Aktif
                        </span>
                        <span class="text-slate-400 text-xs font-medium">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4 bg-white/5 border border-white/10 rounded-2xl p-4 backdrop-blur-sm">
                <div class="text-right">
                    <p class="text-slate-400 text-xs font-medium mb-0.5">Jam sekarang</p>
                    <p class="text-white text-2xl font-bold font-mono tracking-tight" id="clock">--:--</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Hari Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        {{-- Stat 1 --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors text-slate-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375a9 9 0 019 9v.375M10.125 2.25A3.375 3.375 0 0113.5 5.625v1.5c0 .621.504 1.125 1.125 1.125h1.5a3.375 3.375 0 013.375 3.375M9 15l2.25 2.25L15 12" /></svg>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Hari ini</span>
            </div>
            <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['transaksiHariIni'] }}</p>
            <p class="text-sm font-medium text-slate-500 mt-1">Transaksi Selesai</p>
        </div>

        {{-- Stat 2 --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors text-slate-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Hari ini</span>
            </div>
            <p class="text-2xl font-bold text-slate-900 tracking-tight truncate">Rp {{ number_format($stats['pendapatanHariIni'], 0, ',', '.') }}</p>
            <p class="text-sm font-medium text-slate-500 mt-1">Total Pendapatan</p>
        </div>

        {{-- Stat 3 --}}
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 hover:border-slate-300 hover:shadow-md transition-all group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors text-slate-700">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" /></svg>
                </div>
                <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Minggu ini</span>
            </div>
            <p class="text-3xl font-bold text-slate-900 tracking-tight">{{ $stats['transaksiMingguIni'] }}</p>
            <p class="text-sm font-medium text-slate-500 mt-1">Total Transaksi</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="{{ route('kasir.transactions.create') }}"
           class="group relative overflow-hidden rounded-3xl p-6 flex items-center gap-5 transition-all active:scale-[0.98] bg-slate-900 shadow-xl shadow-slate-900/20 hover:bg-slate-800">
            <div class="relative w-14 h-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
            </div>
            <div class="relative flex-1">
                <p class="text-white font-bold text-lg tracking-tight">Transaksi Baru</p>
                <p class="text-slate-400 text-sm font-medium mt-0.5">Penjualan sparepart & servis</p>
            </div>
            <svg class="relative ml-auto w-6 h-6 text-slate-500 group-hover:text-white group-hover:translate-x-1.5 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </a>

        <a href="{{ route('kasir.spareparts.index') }}"
           class="group bg-white hover:border-slate-300 hover:shadow-md rounded-3xl p-6 flex items-center gap-5 transition-all active:scale-[0.98] border border-slate-200">
            <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-slate-900 group-hover:border-slate-900 flex items-center justify-center flex-shrink-0 transition-colors">
                <svg class="w-7 h-7 text-slate-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4.5M10 15h1.5M12 18.75h-4.5m-3-11.25h15m-15 0L6.75 4.5h10.5l1.5 3z" /></svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-slate-900 text-lg tracking-tight">Cek Stok Barang</p>
                <p class="text-slate-500 text-sm font-medium mt-0.5">Lihat ketersediaan produk</p>
            </div>
            <svg class="w-6 h-6 text-slate-300 group-hover:text-slate-900 group-hover:translate-x-1.5 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </a>
    </div>

    {{-- Transaksi Terakhir --}}
    @if($stats['transaksiTerakhir']->count() > 0)
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="text-base font-bold text-slate-900 tracking-tight">Transaksi Terakhir</h3>
            <a href="{{ route('kasir.transactions.index') }}" class="text-sm text-slate-500 hover:text-slate-900 font-semibold flex items-center gap-1 transition-colors">
                Lihat semua
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($stats['transaksiTerakhir'] as $trx)
            <div class="flex items-center justify-between px-6 py-4 hover:bg-slate-50/70 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 shadow-sm group-hover:border-slate-300 transition-colors">
                        @if($trx->tipe_transaksi === 'servis')
                            <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.83m-3.703 3.75a3.375 3.375 0 11-4.773-4.773L9.75 7.5M11.42 15.17l-3.95-3.95m5.903 5.903L15 15.75M8.25 15.75l-4.5 4.5M3 16.5l3-3m0 0l-3-3m3 3H9" /></svg>
                        @else
                            <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-mono font-bold text-slate-900">{{ $trx->no_struk }}</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">
                            <span class="capitalize text-slate-900">{{ $trx->tipe_transaksi }}</span>
                            <span class="mx-1">&bull;</span> 
                            {{ $trx->created_at->locale('id')->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end">
                    <p class="text-sm font-bold text-slate-900 tracking-tight">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                    <a href="{{ route('kasir.transactions.receipt', $trx->id) }}" class="mt-1 text-xs font-semibold text-slate-500 bg-slate-100 hover:bg-slate-200 hover:text-slate-900 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .121.08.232.198.256 3.425.688 6.945.688 10.404 0 .118-.024.198-.135.198-.256V7.03z" /></svg>
                        Cetak Struk
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-12 text-center flex flex-col items-center justify-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
        </div>
        <p class="text-slate-500 font-medium">Belum ada transaksi hari ini</p>
        <a href="{{ route('kasir.transactions.create') }}" class="text-slate-900 text-sm font-bold hover:underline mt-2 inline-flex items-center gap-1.5">
            Mulai transaksi pertama 
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </a>
    </div>
    @endif

</div>

<script>
(function() {
    function updateClock() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2, '0');
        const m = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('clock').textContent = h + ':' + m;
    }
    updateClock();
    setInterval(updateClock, 1000);
})();
</script>
@endsection