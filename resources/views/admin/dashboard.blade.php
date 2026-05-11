@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Greeting Banner --}}
    <div class="rounded-2xl p-6 shadow-sm border border-blue-900/50" style="background:linear-gradient(135deg,#0f172a,#1e2e5f)">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-blue-300 text-sm font-medium">Selamat datang kembali,</p>
                <h1 class="text-white text-2xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</h1>
                <p class="text-blue-400 text-sm mt-1">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }}
                    &mdash; <span class="text-amber-400 font-semibold">Admin</span>
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background:rgba(245,158,11,.15);border:1px solid rgba(245,158,11,.3)">
                <svg class="w-7 h-7 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Transaksi --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.621-3.78l-1.932-1.932A1.5 1.5 0 0 0 14.128 2H5.872a1.5 1.5 0 0 0-1.06.44L2.879 4.372A3 3 0 0 0 2.879 7.121Z"/><path fill-rule="evenodd" d="M2 10.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 .5.5V17a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-6.5Zm3.75 2.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">0</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Transaksi</p>
        </div>
        {{-- Pendapatan --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Zm12 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm13-1a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp 0</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Pendapatan</p>
        </div>
        {{-- Pengeluaran --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h9A1.5 1.5 0 0 1 14 3.5v11.75A2.75 2.75 0 0 0 16.75 18h-12A2.75 2.75 0 0 1 2 15.25V3.5Zm3.75 7a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Zm0 3a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM5 5.75A.75.75 0 0 1 5.75 5h4.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 8.25v-2.5Z" clip-rule="evenodd"/><path d="M16.5 6.5h-1v8.75a1.25 1.25 0 1 0 2.5 0V8a1.5 1.5 0 0 0-1.5-1.5Z"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">Rp 0</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Pengeluaran</p>
        </div>
        {{-- Stok Menipis --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-amber-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Perhatian</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">0</p>
            <p class="text-sm text-gray-500 mt-0.5">Stok Menipis</p>
        </div>
    </div>

    {{-- Placeholder chart --}}
    <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-100 text-center">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-blue-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z" clip-rule="evenodd"/></svg>
        </div>
        <h3 class="text-gray-700 font-semibold mb-1">Grafik & Statistik Akan Tampil Di Sini</h3>
        <p class="text-gray-400 text-sm max-w-sm mx-auto">Grafik tren transaksi dan laporan keuangan tersedia setelah modul transaksi selesai dibangun.</p>
    </div>

</div>
@endsection
