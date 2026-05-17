@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Greeting --}}
    <div class="rounded-2xl p-6 shadow-sm border border-blue-900/50" style="background:linear-gradient(135deg,#0f172a,#1e2e5f)">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-blue-300 text-sm font-medium">Selamat datang kembali,</p>
                <h1 class="text-white text-2xl font-bold mt-0.5">{{ auth()->user()->name }} 👋</h1>
                <p class="text-blue-400 text-sm mt-1">
                    {{ now()->locale('id')->translatedFormat('l, d F Y') }} &mdash;
                    <span class="text-amber-400 font-semibold">Periode: {{ now()->locale('id')->translatedFormat('F Y') }}</span>
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center">
                <svg class="w-7 h-7 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M11.078 2.25c-.917 0-1.699.663-1.85 1.567L9.05 4.889c-.02.12-.115.26-.297.348a7.493 7.493 0 0 0-.986.57c-.166.115-.334.126-.45.083L6.3 5.508a1.875 1.875 0 0 0-2.282.819l-.922 1.597a1.875 1.875 0 0 0 .432 2.385l.84.692c.095.078.17.229.154.43a7.598 7.598 0 0 0 0 1.139c.015.2-.059.352-.153.43l-.841.692a1.875 1.875 0 0 0-.432 2.385l.922 1.597a1.875 1.875 0 0 0 2.282.818l1.019-.382c.115-.043.283-.031.45.082.312.214.641.405.985.57.182.088.277.228.297.35l.178 1.071c.151.904.933 1.567 1.85 1.567h1.844c.916 0 1.699-.663 1.85-1.567l.178-1.072c.02-.12.114-.26.297-.349.344-.165.673-.356.985-.57.167-.114.335-.125.45-.082l1.02.382a1.875 1.875 0 0 0 2.28-.819l.923-1.597a1.875 1.875 0 0 0-.432-2.385l-.84-.692c-.095-.078-.17-.229-.154-.43a7.614 7.614 0 0 0 0-1.139c-.016-.2.059-.352.153-.43l.84-.692c.708-.582.891-1.59.433-2.385l-.922-1.597a1.875 1.875 0 0 0-2.282-.818l-1.02.382c-.114.043-.282.031-.449-.083a7.49 7.49 0 0 0-.985-.57c-.183-.087-.277-.227-.297-.348l-.179-1.072a1.875 1.875 0 0 0-1.85-1.567h-1.843ZM12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
        {{-- Pendapatan --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Zm12 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm13-1a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-xl font-bold text-gray-900 truncate">Rp {{ number_format($stats['totalPendapatan'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Pendapatan</p>
        </div>

        {{-- Transaksi --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.621-3.78l-1.932-1.932A1.5 1.5 0 0 0 14.128 2H5.872a1.5 1.5 0 0 0-1.06.44L2.879 4.372A3 3 0 0 0 2.879 7.121Z"/><path fill-rule="evenodd" d="M2 10.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 .5.5V17a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-6.5Zm3.75 2.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-xl font-bold text-gray-900">{{ number_format($stats['totalTransaksi']) }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Transaksi</p>
        </div>

        {{-- Pengeluaran --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h9A1.5 1.5 0 0 1 14 3.5v11.75A2.75 2.75 0 0 0 16.75 18h-12A2.75 2.75 0 0 1 2 15.25V3.5Zm3.75 7a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Zm0 3a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM5 5.75A.75.75 0 0 1 5.75 5h4.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 8.25v-2.5Z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Bulan ini</span>
            </div>
            <p class="text-xl font-bold text-gray-900 truncate">Rp {{ number_format($stats['totalPengeluaran'], 0, ',', '.') }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Total Pengeluaran</p>
        </div>

        {{-- Stok Menipis --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border {{ $stats['stokMenipis'] > 0 ? 'border-amber-200' : 'border-gray-100' }}">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl {{ $stats['stokMenipis'] > 0 ? 'bg-amber-100' : 'bg-gray-100' }} flex items-center justify-center">
                    <svg class="w-5 h-5 {{ $stats['stokMenipis'] > 0 ? 'text-amber-600' : 'text-gray-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                </div>
                @if($stats['stokMenipis'] > 0)
                    <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">Perhatian</span>
                @else
                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full">Aman</span>
                @endif
            </div>
            <p class="text-xl font-bold {{ $stats['stokMenipis'] > 0 ? 'text-amber-600' : 'text-gray-900' }}">{{ $stats['stokMenipis'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5">Stok Menipis</p>
        </div>
    </div>

    {{-- Laba Bersih Banner --}}
    <div class="rounded-2xl px-6 py-4 {{ $stats['labaBersih'] >= 0 ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200' }} flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 {{ $stats['labaBersih'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
            <p class="text-sm font-semibold {{ $stats['labaBersih'] >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
                Laba Bersih Bulan Ini (Pendapatan - Pengeluaran)
            </p>
        </div>
        <p class="text-lg font-bold {{ $stats['labaBersih'] >= 0 ? 'text-emerald-700' : 'text-red-700' }}">
            Rp {{ number_format(abs($stats['labaBersih']), 0, ',', '.') }}
            @if($stats['labaBersih'] < 0) <span class="text-sm font-normal">(Rugi)</span> @endif
        </p>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Grafik 7 Hari --}}
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-sm font-bold text-gray-700 mb-4">Pendapatan 7 Hari Terakhir</h3>
            <div class="flex items-end gap-2 h-36">
                @php $maxVal = collect($stats['chart'])->max('total') ?: 1; @endphp
                @foreach($stats['chart'] as $day)
                    @php $pct = $maxVal > 0 ? ($day['total'] / $maxVal) * 100 : 0; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1 group">
                        <p class="text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                            Rp {{ number_format($day['total'], 0, ',', '.') }}
                        </p>
                        <div class="w-full rounded-t-lg {{ $day['total'] > 0 ? 'bg-amber-400 hover:bg-amber-500' : 'bg-gray-100' }} transition-colors cursor-default"
                             style="height: {{ max(4, $pct) }}%"></div>
                        <p class="text-xs text-gray-400 text-center">{{ $day['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Stok Menipis List --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-700">Stok Menipis</h3>
                <a href="{{ route('admin.spareparts.index', ['stok_menipis' => 1]) }}" class="text-xs text-amber-500 hover:underline">Lihat semua</a>
            </div>
            @forelse($stokMenipisList as $sp)
                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                    <p class="text-sm text-gray-700 font-medium truncate flex-1">{{ $sp->nama_part }}</p>
                    <span class="ml-2 text-xs font-bold {{ $sp->stok == 0 ? 'text-red-600 bg-red-50' : 'text-amber-600 bg-amber-50' }} px-2 py-0.5 rounded-full flex-shrink-0">
                        {{ $sp->stok }} {{ $sp->satuan }}
                    </span>
                </div>
            @empty
                <div class="text-center py-6">
                    <p class="text-emerald-500 text-sm font-medium">✅ Semua stok aman</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Sparepart Terlaris --}}
    @if($terlaris->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="text-sm font-bold text-gray-700 mb-4">Sparepart Terlaris Bulan Ini</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 rounded-xl">
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500 rounded-l-xl">#</th>
                        <th class="text-left px-3 py-2 text-xs font-semibold text-gray-500">Nama Sparepart</th>
                        <th class="text-right px-3 py-2 text-xs font-semibold text-gray-500">Terjual</th>
                        <th class="text-right px-3 py-2 text-xs font-semibold text-gray-500 rounded-r-xl">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($terlaris as $i => $item)
                    <tr>
                        <td class="px-3 py-2.5 text-gray-400 font-mono text-xs">{{ $i + 1 }}</td>
                        <td class="px-3 py-2.5 font-medium text-gray-800">{{ $item->sparepart->nama_part ?? '—' }}</td>
                        <td class="px-3 py-2.5 text-right text-blue-600 font-semibold">{{ $item->total_qty }} {{ $item->sparepart->satuan ?? '' }}</td>
                        <td class="px-3 py-2.5 text-right text-emerald-600 font-semibold">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
