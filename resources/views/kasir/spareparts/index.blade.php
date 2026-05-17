@extends('layouts.app')

@section('title', 'Cek Stok')
@section('page_title', 'Cek Stok Sparepart')

@section('content')
<div class="space-y-6 text-slate-800">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Stok Sparepart</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Informasi ketersediaan stok produk saat ini (Hanya baca)</p>
    </div>

    {{-- Alert stok menipis --}}
    @if($totalMenipis > 0)
    <div class="flex items-center gap-4 bg-amber-50 border border-amber-200/60 rounded-2xl px-5 py-4 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <p class="text-sm text-amber-800 font-medium">
            <span class="font-bold">{{ $totalMenipis }} produk</span> stoknya berada di bawah batas minimum. Harap segera laporkan ke Admin.
        </p>
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5">
        <form method="GET" action="{{ route('kasir.spareparts.index') }}" class="flex flex-wrap gap-4 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, kode, atau kategori..."
                    class="w-full pl-11 pr-4 py-3 text-sm bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 text-slate-700 font-medium transition-shadow placeholder:text-slate-400">
            </div>
            
            <label class="flex items-center gap-2.5 text-sm font-semibold text-slate-600 cursor-pointer select-none py-2">
                <input type="checkbox" name="stok_menipis" value="1" {{ request('stok_menipis') ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900 transition-colors cursor-pointer">
                Stok Menipis Saja
            </label>
            
            <div class="flex items-center gap-2">
                <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-slate-900/10">
                    Cari Data
                </button>
                @if(request('search') || request('stok_menipis'))
                    <a href="{{ route('kasir.spareparts.index') }}" class="px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Kode Produk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama & Merek</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">Harga Jual</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($spareparts as $sp)
                    <tr class="{{ $sp->is_stok_menipis ? 'bg-amber-50/30' : '' }} hover:bg-slate-50/70 transition-colors group">
                        {{-- Kode --}}
                        <td class="px-6 py-4 font-mono font-bold text-slate-500 whitespace-nowrap">
                            {{ $sp->kode_part }}
                        </td>
                        
                        {{-- Nama & Merek --}}
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900">{{ $sp->nama_part }}</p>
                            @if($sp->merek)
                                <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $sp->merek }}</p>
                            @endif
                        </td>
                        
                        {{-- Kategori --}}
                        <td class="px-6 py-4">
                            @if($sp->kategori)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-slate-100 border border-slate-200 text-slate-600 shadow-sm">
                                    {{ $sp->kategori }}
                                </span>
                            @else
                                <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        
                        {{-- Harga Jual --}}
                        <td class="px-6 py-4 text-right font-bold text-slate-900 tracking-tight whitespace-nowrap">
                            Rp {{ number_format($sp->harga_jual, 0, ',', '.') }}
                        </td>
                        
                        {{-- Status Stok --}}
                        <td class="px-6 py-4 text-center">
                            @if($sp->stok === 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-red-50 text-red-600 border border-red-100 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Habis
                                </span>
                            @elseif($sp->is_stok_menipis)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                    {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4.5M10 15h1.5M12 18.75h-4.5m-3-11.25h15m-15 0L6.75 4.5h10.5l1.5 3z" /></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Tidak ada data sparepart yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($spareparts->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $spareparts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection