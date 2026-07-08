@extends('layouts.app')

@section('title', 'Edit Jasa Servis')
@section('page_title', 'Edit Jasa Servis')

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">

        <div>
            <h2 class="text-lg font-bold text-gray-800">Edit: {{ $jasaServis->nama_jasa }}</h2>
            <p class="text-sm text-gray-400 mt-0.5">Perbarui detail pekerjaan dan estimasi biayanya</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $err)<p>• {{ $err }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('admin.jasa-servis.update', $jasaServis->id) }}" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label for="nama_jasa" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                    Nama Pekerjaan <span class="text-red-500">*</span>
                </label>
                <input id="nama_jasa" type="text" name="nama_jasa" value="{{ old('nama_jasa', $jasaServis->nama_jasa) }}"
                    placeholder="cth: Cuci Evaporator"
                    class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 @error('nama_jasa') border-red-300 @enderror">
            </div>

            <div>
                <label for="estimasi_biaya" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                    Estimasi Biaya (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium text-sm">Rp</span>
                    <input id="estimasi_biaya" type="number" name="estimasi_biaya" value="{{ old('estimasi_biaya', $jasaServis->estimasi_biaya) }}"
                        min="0" step="1000"
                        class="w-full pl-12 pr-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 @error('estimasi_biaya') border-red-300 @enderror">
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-semibold text-gray-500 mb-1.5 uppercase tracking-wider">
                    Keterangan <span class="text-gray-300">(opsional)</span>
                </label>
                <textarea id="keterangan" name="keterangan" rows="2"
                    placeholder="Catatan tambahan jika ada..."
                    class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none">{{ old('keterangan', $jasaServis->keterangan) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <input id="is_aktif" type="checkbox" name="is_aktif" value="1"
                    {{ old('is_aktif', $jasaServis->is_aktif) ? 'checked' : '' }}
                    class="w-4 h-4 rounded accent-amber-500">
                <label for="is_aktif" class="text-sm font-medium text-gray-700 cursor-pointer">Jasa ini aktif (tersedia di POS)</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" id="btn-update-jasa"
                    class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl shadow shadow-amber-500/30 transition-all active:scale-95">
                    Perbarui
                </button>
                <a href="{{ route('admin.jasa-servis.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
