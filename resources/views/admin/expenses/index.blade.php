@extends('layouts.app')

@section('title', 'Pengeluaran')
@section('page_title', 'Pengeluaran Operasional')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Daftar Pengeluaran</h1>
            <p class="text-sm text-gray-400 mt-0.5">Pencatatan pengeluaran operasional bengkel</p>
        </div>
        <a href="{{ route('admin.expenses.create') }}"
           id="btn-tambah-pengeluaran"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-xl shadow shadow-red-500/30 transition-all active:scale-95">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            Catat Pengeluaran
        </a>
    </div>

    {{-- Total Bulan Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Total Pengeluaran Bulan Ini</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->locale('id')->translatedFormat('F Y') }}</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.expenses.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-48">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau kategori..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <div>
                <input type="month" name="bulan" value="{{ request('bulan') }}"
                    class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-400">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
            @if(request('search') || request('bulan'))
                <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Nama Pengeluaran</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Kategori</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Jumlah</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Dicatat Oleh</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $expense)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <p class="font-medium text-gray-700">{{ $expense->tanggal_pengeluaran->format('d M Y') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $expense->nama_pengeluaran }}</p>
                            @if($expense->keterangan)
                                <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ $expense->keterangan }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($expense->kategori)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                    {{ $expense->kategori }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-red-600 whitespace-nowrap">
                            Rp {{ number_format($expense->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $expense->admin->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-center whitespace-nowrap">
                            <a href="{{ route('admin.expenses.edit', $expense) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z"/><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z"/></svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $expense) }}" class="inline"
                                  onsubmit="return confirm('Hapus pengeluaran ini?')">
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
                        <td colspan="6" class="px-4 py-16 text-center">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-gray-400 font-medium">Belum ada data pengeluaran</p>
                            <a href="{{ route('admin.expenses.create') }}" class="text-sm text-red-500 hover:underline mt-1 inline-block">Catat pengeluaran pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($expenses->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
