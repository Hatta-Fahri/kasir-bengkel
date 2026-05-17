@extends('layouts.app')

@section('title', 'Cek Stok')
@section('page_title', 'Cek Stok Sparepart')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-800">Stok Sparepart</h1>
        <p class="text-sm text-gray-400 mt-0.5">Informasi ketersediaan stok saat ini (hanya baca)</p>
    </div>

    {{-- Alert stok menipis --}}
    @if($totalMenipis > 0)
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
        <p class="text-sm text-amber-700 font-medium">
            <span class="font-bold">{{ $totalMenipis }} sparepart</span> stoknya di bawah batas minimum. Harap laporkan ke Admin.
        </p>
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('kasir.spareparts.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-52">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, kode, kategori..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                <input type="checkbox" name="stok_menipis" value="1" {{ request('stok_menipis') ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-amber-500">
                Stok menipis saja
            </label>
            <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
            @if(request('search') || request('stok_menipis'))
                <a href="{{ route('kasir.spareparts.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Kode</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama Sparepart</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Kategori</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Harga Jual</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($spareparts as $sp)
                    <tr class="{{ $sp->is_stok_menipis ? 'bg-amber-50/40' : '' }} hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $sp->kode_part }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $sp->nama_part }}</p>
                            @if($sp->merek)
                                <p class="text-xs text-gray-400">{{ $sp->merek }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($sp->kategori)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $sp->kategori }}</span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($sp->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($sp->stok === 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    ❌ Habis
                                </span>
                            @elseif($sp->is_stok_menipis)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                    ⚠ {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    ✅ {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <p class="text-gray-400 font-medium">Tidak ada sparepart ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($spareparts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $spareparts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
