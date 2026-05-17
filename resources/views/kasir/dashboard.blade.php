@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-5">

    {{-- Greeting Banner --}}
    <div class="relative overflow-hidden rounded-2xl p-6 shadow-sm" style="background:linear-gradient(135deg,#022c22 0%,#065f46 50%,#047857 100%)">
        <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 80% 20%,#34d399 0%,transparent 50%),radial-gradient(circle at 20% 80%,#10b981 0%,transparent 50%)"></div>
        <div class="relative flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-emerald-300 text-sm font-medium tracking-wide">Selamat datang kembali 👋</p>
                <h1 class="text-white text-2xl font-bold mt-1 tracking-tight">{{ auth()->user()->name }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-900 bg-emerald-300 px-2.5 py-0.5 rounded-full">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.403 12.652a3 3 0 0 0 0-5.304 3 3 0 0 0-3.75-3.751 3 3 0 0 0-5.305 0 3 3 0 0 0-3.751 3.75 3 3 0 0 0 0 5.305 3 3 0 0 0 3.75 3.751 3 3 0 0 0 5.305 0 3 3 0 0 0 3.751-3.75Zm-2.546-4.46a.75.75 0 0 0-1.214-.883l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                        Kasir Aktif
                    </span>
                    <span class="text-emerald-400 text-xs">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-emerald-400 text-xs">Jam sekarang</p>
                    <p class="text-white text-2xl font-bold font-mono" id="clock">--:--</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-emerald-400/20 border border-emerald-400/30 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25Z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Hari Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.621-3.78l-1.932-1.932A1.5 1.5 0 0 0 14.128 2H5.872a1.5 1.5 0 0 0-1.06.44L2.879 4.372A3 3 0 0 0 2.879 7.121Z"/><path fill-rule="evenodd" d="M2 10.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 .5.5V17a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-6.5Zm3.75 2.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Hari ini</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['transaksiHariIni'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Transaksi Selesai</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Zm12 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm13-1a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Hari ini</span>
            </div>
            <p class="text-xl font-bold text-emerald-600 truncate">Rp {{ number_format($stats['pendapatanHariIni'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Pendapatan</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 0 1 .75.75V4h7V2.75a.75.75 0 0 1 1.5 0V4h.25A2.75 2.75 0 0 1 18 6.75v8.5A2.75 2.75 0 0 1 15.25 18H4.75A2.75 2.75 0 0 1 2 15.25v-8.5A2.75 2.75 0 0 1 4.75 4H5V2.75A.75.75 0 0 1 5.75 2Zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Minggu ini</span>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['transaksiMingguIni'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Transaksi Minggu Ini</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('kasir.transactions.create') }}"
           class="group relative overflow-hidden rounded-2xl p-5 flex items-center gap-4 transition-all active:scale-95 shadow-lg shadow-amber-500/20"
           style="background:linear-gradient(135deg,#d97706,#f59e0b)">
            <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity" style="background:linear-gradient(135deg,#b45309,#d97706)"></div>
            <div class="relative w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            </div>
            <div class="relative">
                <p class="text-white font-bold text-base">Transaksi Baru</p>
                <p class="text-amber-100 text-sm">Penjualan sparepart & servis</p>
            </div>
            <svg class="relative ml-auto w-5 h-5 text-white/50 group-hover:text-white group-hover:translate-x-1 transition-all" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
        </a>

        <a href="{{ route('kasir.spareparts.index') }}"
           class="group bg-white hover:shadow-md rounded-2xl p-5 flex items-center gap-4 transition-all border border-gray-100">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 group-hover:bg-emerald-500 flex items-center justify-center flex-shrink-0 transition-colors">
                <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 3.998v8.649l6.498-3.582A.75.75 0 0 0 18 14.9V6.443ZM9.25 19.09V10.44L2 6.443V14.9a.75.75 0 0 0 .752.608L9.25 19.09Z"/></svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-800">Cek Stok</p>
                <p class="text-gray-400 text-sm">Lihat ketersediaan barang</p>
            </div>
            <svg class="w-5 h-5 text-gray-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/></svg>
        </a>
    </div>

    {{-- Transaksi Terakhir --}}
    @if($stats['transaksiTerakhir']->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Transaksi Terakhir Saya</h3>
            <a href="{{ route('kasir.transactions.index') }}" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold hover:underline">Lihat semua →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($stats['transaksiTerakhir'] as $trx)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/70 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $trx->tipe_transaksi === 'servis' ? 'bg-blue-100' : 'bg-amber-100' }}">
                        @if($trx->tipe_transaksi === 'servis')
                            <svg class="w-4 h-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.5 10a4.5 4.5 0 0 0 4.284-5.882c-.105-.324-.51-.391-.752-.15L15.34 6.66a.454.454 0 0 1-.493.11 3.01 3.01 0 0 1-1.618-1.616.455.455 0 0 1 .11-.494l2.694-2.692c.24-.241.174-.647-.15-.752a4.5 4.5 0 0 0-5.873 4.575c.055.873-.128 1.808-.8 2.368l-7.23 6.024a2.724 2.724 0 1 0 3.837 3.837l6.024-7.23c.56-.672 1.495-.855 2.368-.8.096.007.193.01.291.01Z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-4 h-4 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.621-3.78l-1.932-1.932A1.5 1.5 0 0 0 14.128 2H5.872a1.5 1.5 0 0 0-1.06.44L2.879 4.372A3 3 0 0 0 2.879 7.121Z"/><path fill-rule="evenodd" d="M2 10.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 .5.5V17a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-6.5Zm3.75 2.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" clip-rule="evenodd"/></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-mono font-bold text-gray-700">{{ $trx->no_struk }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span class="capitalize {{ $trx->tipe_transaksi === 'servis' ? 'text-blue-500' : 'text-amber-500' }}">{{ $trx->tipe_transaksi }}</span>
                            &bull; {{ $trx->created_at->locale('id')->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                    <a href="{{ route('kasir.transactions.receipt', $trx->id) }}" class="text-xs text-blue-500 hover:underline font-medium">Cetak struk</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-7 h-7 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25Z"/></svg>
        </div>
        <p class="text-gray-500 font-medium">Belum ada transaksi hari ini</p>
        <a href="{{ route('kasir.transactions.create') }}" class="text-emerald-600 text-sm font-semibold hover:underline mt-1 inline-block">Mulai transaksi pertama →</a>
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
