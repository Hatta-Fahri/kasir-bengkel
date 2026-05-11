@extends('layouts.app')

@section('title', 'Master Sparepart')
@section('page_title', 'Master Sparepart')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Daftar Sparepart</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola data sparepart dan harga bengkel</p>
        </div>
        <a href="{{ route('admin.spareparts.create') }}"
           id="btn-tambah-sparepart"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow shadow-amber-500/30 transition-all active:scale-95">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            Tambah Sparepart
        </a>
    </div>

    {{-- Stats bar --}}
    @if($totalMenipis > 0)
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
        <p class="text-sm text-amber-700 font-medium">
            <span class="font-bold">{{ $totalMenipis }} sparepart</span> memiliki stok di bawah batas minimum.
        </p>
        <a href="{{ route('admin.spareparts.index', ['stok_menipis' => 1]) }}" class="ml-auto text-xs text-amber-600 underline font-medium hover:text-amber-800">Lihat</a>
    </div>
    @endif

    {{-- Filter & Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.spareparts.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-52">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                <input id="search" type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama, kode, merek..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                <input type="checkbox" name="stok_menipis" value="1" {{ request('stok_menipis') ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-amber-500">
                Stok menipis saja
            </label>
            <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
            @if(request('search') || request('stok_menipis'))
                <a href="{{ route('admin.spareparts.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
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
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Harga Beli</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Harga Jual (+10%)</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Stok</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($spareparts as $sp)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $sp->kode_part }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $sp->nama_part }}</p>
                            @if($sp->merek)
                                <p class="text-xs text-gray-400">{{ $sp->merek }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($sp->kategori)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $sp->kategori }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-medium text-gray-700 whitespace-nowrap">
                            Rp {{ number_format($sp->harga_beli, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-emerald-600 whitespace-nowrap">
                            Rp {{ number_format($sp->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($sp->is_stok_menipis)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" fill="currentColor"><path fill-rule="evenodd" d="M5.22 1.53a.75.75 0 0 1 1.56 0l.453 2.269a.75.75 0 0 1-.736.911H5.503a.75.75 0 0 1-.736-.911L5.22 1.53ZM6 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                                    {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                    {{ $sp->stok }} {{ $sp->satuan }}
                                </span>
                            @endif
                            <p class="text-xs text-gray-300 mt-0.5">min {{ $sp->stok_minimum }}</p>
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <a href="{{ route('admin.spareparts.edit', $sp) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z"/><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.spareparts.destroy', $sp) }}" class="inline"
                                  onsubmit="return confirm('Hapus sparepart \"{{ addslashes($sp->nama_part) }}\"?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors ml-1">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/></svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925Z"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium">Belum ada data sparepart</p>
                            <a href="{{ route('admin.spareparts.create') }}" class="text-sm text-amber-500 hover:underline mt-1 inline-block">Tambah sparepart pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($spareparts->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $spareparts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
