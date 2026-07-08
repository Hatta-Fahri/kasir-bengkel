@extends('layouts.app')

@section('title', 'Master Jasa Servis')
@section('page_title', 'Master Jasa Servis')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Daftar Jasa Servis</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola master data jenis pekerjaan servis dan estimasi biayanya</p>
        </div>
        <a href="{{ route('admin.jasa-servis.create') }}"
           id="btn-tambah-jasa"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow shadow-amber-500/30 transition-all active:scale-95">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
            Tambah Jasa
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-1-5a1 1 0 1 0 2 0V9a1 1 0 1 0-2 0v4Zm1-7a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Search --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.jasa-servis.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-52">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd"/></svg>
                <input id="search-jasa" type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama jasa..."
                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-xl transition-colors">Cari</button>
            @if(request('search'))
                <a href="{{ route('admin.jasa-servis.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Jasa</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estimasi Biaya</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($jasaServisList as $jasa)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-5 py-4 text-gray-400 font-medium">{{ $jasaServisList->firstItem() + $loop->index }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-gray-800">{{ $jasa->nama_jasa }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-gray-900">Rp {{ number_format($jasa->estimasi_biaya, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 max-w-xs truncate">{{ $jasa->keterangan ?? '-' }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($jasa->is_aktif)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.jasa-servis.edit', $jasa->id) }}"
                                   id="btn-edit-jasa-{{ $jasa->id }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z"/><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z"/></svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.jasa-servis.destroy', $jasa->id) }}"
                                      onsubmit="return confirm('Hapus jasa {{ $jasa->nama_jasa }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            id="btn-hapus-jasa-{{ $jasa->id }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z" clip-rule="evenodd"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z"/></svg>
                            <p class="text-sm font-medium">Belum ada jasa servis.</p>
                            <a href="{{ route('admin.jasa-servis.create') }}" class="mt-2 inline-block text-amber-600 text-sm font-semibold hover:underline">Tambah Jasa Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jasaServisList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $jasaServisList->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
