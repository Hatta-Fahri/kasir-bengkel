@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-5">

    {{-- Greeting --}}
    <div class="rounded-2xl p-6 shadow-sm border border-emerald-900/30" style="background:linear-gradient(135deg,#064e3b,#065f46)">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-emerald-300 text-sm font-medium">Selamat datang kembali,</p>
                <h1 class="text-white text-2xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</h1>
                <p class="text-emerald-400 text-sm mt-1">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25Z"/></svg>
            </div>
        </div>
    </div>

    {{-- Stats Hari Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Transaksi Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['transaksiHariIni'] }}</p>
            <p class="text-xs text-gray-400 mt-1">transaksi selesai</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Pendapatan Hari Ini</p>
            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['pendapatanHariIni'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">dari transaksi Anda</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Transaksi Minggu Ini</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['transaksiMingguIni'] }}</p>
            <p class="text-xs text-gray-400 mt-1">transaksi selesai</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('kasir.transactions.create') }}"
           class="group bg-amber-500 hover:bg-amber-600 rounded-2xl p-5 shadow-sm shadow-amber-500/30 flex items-center gap-4 transition-all active:scale-95">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            </div>
            <div>
                <p class="text-white font-bold">Transaksi Baru</p>
                <p class="text-white/70 text-sm">Penjualan & Servis</p>
            </div>
        </a>
        <a href="{{ route('kasir.spareparts.index') }}"
           class="group bg-white hover:border-emerald-200 rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4 transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-500 transition-colors">
                <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 3.998v8.649l6.498-3.582A.75.75 0 0 0 18 14.9V6.443ZM9.25 19.09V10.44L2 6.443V14.9a.75.75 0 0 0 .752.608L9.25 19.09Z"/></svg>
            </div>
            <div>
                <p class="font-bold text-gray-800">Cek Stok</p>
                <p class="text-gray-400 text-sm">Lihat ketersediaan</p>
            </div>
        </a>
    </div>

    {{-- Transaksi Terakhir --}}
    @if($stats['transaksiTerakhir']->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-700">Transaksi Terakhir</h3>
            <a href="{{ route('kasir.transactions.index') }}" class="text-xs text-amber-500 hover:underline">Lihat semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($stats['transaksiTerakhir'] as $trx)
            <div class="flex items-center justify-between px-5 py-3">
                <div>
                    <p class="text-sm font-mono font-bold text-gray-700">{{ $trx->no_struk }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        <span class="capitalize {{ $trx->tipe_transaksi === 'servis' ? 'text-blue-500' : 'text-amber-500' }}">{{ $trx->tipe_transaksi }}</span>
                        &bull; {{ $trx->created_at->locale('id')->diffForHumans() }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-emerald-600">Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}</p>
                    <a href="{{ route('kasir.transactions.receipt', $trx->id) }}" class="text-xs text-blue-500 hover:underline">Lihat struk</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">
        <p class="text-gray-400 text-sm">Belum ada transaksi hari ini.</p>
        <a href="{{ route('kasir.transactions.create') }}" class="text-amber-500 text-sm hover:underline mt-1 inline-block">Mulai transaksi pertama</a>
    </div>
    @endif

</div>
@endsection
