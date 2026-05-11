@extends('layouts.app')

@section('title', 'Tambah Sparepart')
@section('page_title', 'Tambah Sparepart')

@section('content')
<div class="max-w-2xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-5">
        <a href="{{ route('admin.spareparts.index') }}" class="hover:text-amber-500 transition-colors">Master Sparepart</a>
        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
        <span class="text-gray-600 font-medium">Tambah Baru</span>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Data Sparepart Baru</h2>
        <p class="text-sm text-gray-400 mb-6">Kode part akan di-generate otomatis jika dikosongkan. Harga jual = Harga beli + 10%.</p>

        <form method="POST" action="{{ route('admin.spareparts.store') }}" id="form-tambah-sparepart">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Nama Part --}}
                <div class="sm:col-span-2">
                    <label for="nama_part" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Sparepart <span class="text-red-500">*</span>
                    </label>
                    <input id="nama_part" name="nama_part" type="text"
                        value="{{ old('nama_part') }}"
                        placeholder="Contoh: Filter Oli Toyota Avanza"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('nama_part') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('nama_part') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Kode Part --}}
                <div>
                    <label for="kode_part" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kode Part
                        <span class="text-gray-400 font-normal">(opsional, auto-generate)</span>
                    </label>
                    <input id="kode_part" name="kode_part" type="text"
                        value="{{ old('kode_part') }}"
                        placeholder="SP-0001"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('kode_part') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 font-mono">
                    @error('kode_part') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Merek --}}
                <div>
                    <label for="merek" class="block text-sm font-medium text-gray-700 mb-1.5">Merek</label>
                    <input id="merek" name="merek" type="text"
                        value="{{ old('merek') }}"
                        placeholder="Contoh: Toyota, Bosch, NGK"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                    <input id="kategori" name="kategori" type="text"
                        value="{{ old('kategori') }}"
                        placeholder="Contoh: Oli, Filter, Busi, Kampas Rem"
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>

                {{-- Satuan --}}
                <div>
                    <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <select id="satuan" name="satuan"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('satuan') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                        @foreach(['pcs','liter','set','pasang','meter','kg','roll'] as $s)
                            <option value="{{ $s }}" {{ old('satuan', 'pcs') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                    @error('satuan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Harga Beli --}}
                <div>
                    <label for="harga_beli" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Harga Beli (HPP) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
                        <input id="harga_beli" name="harga_beli" type="number" min="0" step="100"
                            value="{{ old('harga_beli') }}"
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border {{ $errors->has('harga_beli') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    @error('harga_beli') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Preview Harga Jual --}}
                <div class="sm:col-span-2">
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                        <p class="text-sm text-emerald-700">
                            Harga jual otomatis (HPP + 10%):
                            <span id="preview-harga-jual" class="font-bold">Rp 0</span>
                        </p>
                    </div>
                </div>

                {{-- Stok --}}
                <div>
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok Awal <span class="text-red-500">*</span>
                    </label>
                    <input id="stok" name="stok" type="number" min="0"
                        value="{{ old('stok', 0) }}"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('stok') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('stok') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Stok Minimum --}}
                <div>
                    <label for="stok_minimum" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Stok Minimum <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal">(batas notifikasi)</span>
                    </label>
                    <input id="stok_minimum" name="stok_minimum" type="number" min="0"
                        value="{{ old('stok_minimum', 5) }}"
                        class="w-full px-4 py-2.5 text-sm border {{ $errors->has('stok_minimum') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                    @error('stok_minimum') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Keterangan --}}
                <div class="sm:col-span-2">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1.5">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        placeholder="Catatan tambahan tentang sparepart ini..."
                        class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit" id="btn-simpan-sparepart"
                    class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow shadow-amber-500/20 transition-all active:scale-95">
                    Simpan Sparepart
                </button>
                <a href="{{ route('admin.spareparts.index') }}"
                    class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Preview harga jual realtime (HPP + 10%)
    const hargaBeliInput = document.getElementById('harga_beli');
    const previewEl      = document.getElementById('preview-harga-jual');

    function updatePreview() {
        const hargaBeli = parseFloat(hargaBeliInput.value) || 0;
        const hargaJual = Math.round(hargaBeli * 1.10);
        previewEl.textContent = 'Rp ' + hargaJual.toLocaleString('id-ID');
    }

    hargaBeliInput.addEventListener('input', updatePreview);
    updatePreview();
</script>
@endpush
@endsection
