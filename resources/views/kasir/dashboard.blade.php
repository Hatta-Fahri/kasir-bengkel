@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Greeting Banner --}}
    <div class="rounded-2xl p-6 shadow-sm border border-emerald-900/30" style="background:linear-gradient(135deg,#064e3b,#065f46)">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-emerald-300 text-sm font-medium">Selamat datang kembali,</p>
                <h1 class="text-white text-2xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</h1>
                <p class="text-emerald-400 text-sm mt-1">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                    &mdash; <span class="text-amber-400 font-semibold">Kasir</span>
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3)">
                <svg class="w-7 h-7 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25ZM3.75 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0ZM16.5 20.25a1.5 1.5 0 1 1 3 0 1.5 1.5 0 0 1-3 0Z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="#" class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-amber-200 hover:shadow-md transition-all duration-200">
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3 group-hover:bg-amber-500 transition-colors">
                <svg class="w-5 h-5 text-amber-600 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v2.5h-2.5a.75.75 0 0 0 0 1.5h2.5v2.5a.75.75 0 0 0 1.5 0v-2.5h2.5a.75.75 0 0 0 0-1.5h-2.5v-2.5Z" clip-rule="evenodd"/></svg>
            </div>
            <p class="font-semibold text-gray-800 text-sm">Transaksi Baru</p>
            <p class="text-gray-400 text-xs mt-0.5">Penjualan & Servis</p>
        </a>
        <a href="#" class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition-all duration-200">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3 group-hover:bg-blue-500 transition-colors">
                <svg class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h9A1.5 1.5 0 0 1 14 3.5v11.75A2.75 2.75 0 0 0 16.75 18h-12A2.75 2.75 0 0 1 2 15.25V3.5Zm3.75 7a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Zm0 3a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM5 5.75A.75.75 0 0 1 5.75 5h4.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 8.25v-2.5Z" clip-rule="evenodd"/><path d="M16.5 6.5h-1v8.75a1.25 1.25 0 1 0 2.5 0V8a1.5 1.5 0 0 0-1.5-1.5Z"/></svg>
            </div>
            <p class="font-semibold text-gray-800 text-sm">Riwayat Transaksi</p>
            <p class="text-gray-400 text-xs mt-0.5">Lihat & cetak struk</p>
        </a>
        <a href="#" class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-emerald-200 hover:shadow-md transition-all duration-200">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3 group-hover:bg-emerald-500 transition-colors">
                <svg class="w-5 h-5 text-emerald-600 group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 3.998v8.649l6.498-3.582A.75.75 0 0 0 18 14.9V6.443ZM9.25 19.09V10.44L2 6.443V14.9a.75.75 0 0 0 .752.608L9.25 19.09Z"/></svg>
            </div>
            <p class="font-semibold text-gray-800 text-sm">Cek Stok</p>
            <p class="text-gray-400 text-xs mt-0.5">Lihat ketersediaan</p>
        </a>
    </div>

    {{-- Stats ringkas --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Transaksi Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900">0</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Pendapatan Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900">Rp 0</p>
        </div>
    </div>

</div>
@endsection
