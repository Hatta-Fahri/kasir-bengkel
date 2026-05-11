@extends('layouts.app')

@section('title', 'Catat Pengeluaran')
@section('page_title', 'Catat Pengeluaran')

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
        <a href="{{ route('admin.expenses.index') }}" class="hover:text-red-500 transition-colors">Pengeluaran</a>
        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
        <span class="text-gray-600 font-medium">Catat Baru</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Data Pengeluaran Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Pengeluaran akan otomatis tercatat atas nama akun Anda.</p>

        <form method="POST" action="{{ route('admin.expenses.store') }}" id="form-tambah-pengeluaran">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Nama Pengeluaran --}}
                <div class="sm:col-span-2">
                    <label for="nama_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Pengeluaran <span class="text-red-500">*</span>
                    </label>
                    <input id="nama_pengeluaran" name="nama_pengeluaran" type="text"
                        value="{{ old('nama_pengeluaran') }}"
                        placeholder="Contoh: Tagihan Listrik Bulan Mei"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('nama_pengeluaran') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                    @error('nama_pengeluaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                    <input id="kategori" name="kategori" type="text"
                        value="{{ old('kategori') }}"
                        placeholder="Contoh: Listrik, Gaji, Perawatan"
                        list="kategori-list"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                    <datalist id="kategori-list">
                        <option value="Listrik">
                        <option value="Air">
                        <option value="Internet">
                        <option value="Gaji Karyawan">
                        <option value="Perawatan Gedung">
                        <option value="Pembelian Perlengkapan">
                        <option value="Lain-lain">
                    </datalist>
                </div>

                {{-- Tanggal Pengeluaran --}}
                <div>
                    <label for="tanggal_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Pengeluaran <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_pengeluaran" name="tanggal_pengeluaran" type="date"
                        value="{{ old('tanggal_pengeluaran', now()->toDateString()) }}"
                        max="{{ now()->toDateString() }}"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('tanggal_pengeluaran') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                    @error('tanggal_pengeluaran') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah --}}
                <div class="sm:col-span-2">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jumlah (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input id="jumlah" name="jumlah" type="number" min="1" step="1000"
                            value="{{ old('jumlah') }}"
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border {{ $errors->has('jumlah') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
                    </div>
                    @error('jumlah') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Keterangan --}}
                <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        placeholder="Catatan tambahan (opsional)..."
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400 resize-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" id="btn-simpan-pengeluaran"
                    class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl shadow shadow-red-500/20 transition-all active:scale-95">
                    Simpan Pengeluaran
                </button>
                <a href="{{ route('admin.expenses.index') }}"
                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
