@extends('layouts.app')

@section('title', 'Prediksi Sparepart')
@section('page_title', 'Prediksi Kebutuhan Sparepart')

@section('content')
<div class="space-y-5">

    {{-- ============================================================ --}}
    {{-- HEADER                                                        --}}
    {{-- ============================================================ --}}
    <div class="rounded-2xl p-6 shadow-sm border border-purple-900/40" style="background:linear-gradient(135deg,#1e1035,#2d1b69)">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-purple-300 text-xs font-semibold uppercase tracking-widest">Powered by Facebook Prophet</span>
                </div>
                <h1 class="text-white text-xl font-bold">Prediksi Kebutuhan Sparepart</h1>
                <p class="text-purple-300 text-sm mt-1">Estimasi kebutuhan berbasis data historis penjualan per bulan</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <form method="POST" action="{{ route('admin.predictions.generate') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-400 text-white text-xs font-semibold rounded-lg transition-colors whitespace-nowrap">
                        Generate Prediksi
                    </button>
                </form>
                <div class="flex flex-col items-end gap-1 text-xs text-purple-300">
                    @if($diGeneratePada)
                        <span>📅 Di-generate: <span class="text-white font-medium">{{ $diGeneratePada->locale('id')->translatedFormat('d F Y H:i') }}</span></span>
                        <span>🔖 Versi model: <span class="text-purple-200 font-mono font-medium">{{ $versiModel }}</span></span>
                    @else
                        <span class="text-purple-400 italic">Belum ada data prediksi</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- FILTER                                                        --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.predictions.index') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Pilih Bulan --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Bulan Prediksi</label>
                @if($bulanTersedia->count() > 0)
                    <select name="bulan" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 bg-white min-w-44">
                        @foreach($bulanTersedia as $b)
                            <option value="{{ $b->bulan_key }}" {{ request('bulan', $bulan->format('Y-m')) === $b->bulan_key ? 'selected' : '' }}>
                                {{ $b->bulan_label }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <input type="month" name="bulan" value="{{ request('bulan', $bulan->format('Y-m')) }}"
                        class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400">
                @endif
            </div>

            {{-- Filter Sparepart --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Filter Sparepart</label>
                <select name="sparepart_id" class="px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 bg-white min-w-52">
                    <option value="">Semua Sparepart</option>
                    @foreach($spareparts as $sp)
                        <option value="{{ $sp->id }}" {{ request('sparepart_id') == $sp->id ? 'selected' : '' }}>
                            {{ $sp->nama_part }} ({{ $sp->kode_part }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-purple-700 hover:bg-purple-800 text-white text-sm font-semibold rounded-xl transition-colors">
                Tampilkan
            </button>
            @if(request('bulan') || request('sparepart_id'))
                <a href="{{ route('admin.predictions.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    @if($predictions->count() > 0)

    {{-- ============================================================ --}}
    {{-- RINGKASAN                                                      --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Total Sparepart Diprediksi</p>
            <p class="text-3xl font-bold text-gray-900">{{ $predictions->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $bulan->locale('id')->translatedFormat('F Y') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border {{ $totalPerluRestok > 0 ? 'border-red-100' : 'border-emerald-100' }}">
            <p class="text-xs text-gray-400 mb-1">Perlu Restock</p>
            <p class="text-3xl font-bold {{ $totalPerluRestok > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $totalPerluRestok }}</p>
            <p class="text-xs {{ $totalPerluRestok > 0 ? 'text-red-400' : 'text-gray-400' }} mt-1">
                {{ $totalPerluRestok > 0 ? 'stok tidak mencukupi prediksi' : 'semua aman' }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-emerald-100">
            <p class="text-xs text-gray-400 mb-1">Stok Cukup</p>
            <p class="text-3xl font-bold text-emerald-600">{{ $totalCukup }}</p>
            <p class="text-xs text-emerald-400 mt-1">stok melebihi estimasi</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ALERT REKOMENDASI                                             --}}
    {{-- ============================================================ --}}
    @if($totalPerluRestok > 0)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-bold text-red-700 mb-1">{{ $totalPerluRestok }} sparepart perlu segera di-restock</p>
                <p class="text-xs text-red-600">
                    Model Prophet memprediksikan kebutuhan pada bulan <strong>{{ $bulan->locale('id')->translatedFormat('F Y') }}</strong>
                    melebihi stok yang tersedia saat ini. Segera lakukan pembelian.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- TABEL PREDIKSI & REKOMENDASI                                  --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="text-sm font-bold text-gray-700">Detail Prediksi — {{ $bulan->locale('id')->translatedFormat('F Y') }}</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Sparepart</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Stok Sekarang</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Estimasi Bulan Ini</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Total Kumulatif*</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Confidence Interval</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600 whitespace-nowrap">Rekomendasi Beli</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($predictions as $p)
                    <tr class="{{ $p->perlu_restok ? 'bg-red-50/30' : '' }} hover:bg-gray-50/70 transition-colors">
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-gray-800">{{ $p->sparepart->nama_part ?? '—' }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $p->sparepart->kode_part ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <span class="font-bold {{ $p->perlu_restok ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $p->stok_saat_ini }}
                            </span>
                            <span class="text-xs text-gray-400 ml-0.5">{{ $p->sparepart->satuan ?? '' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <span class="font-bold text-purple-700">{{ number_format($p->estimasi_kebutuhan, 1) }}</span>
                            <span class="text-xs text-gray-400 ml-0.5">{{ $p->sparepart->satuan ?? '' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <span class="font-bold {{ $p->perlu_restok ? 'text-red-600' : 'text-gray-800' }}">{{ number_format($p->kebutuhan_kumulatif, 1) }}</span>
                            <span class="text-xs text-gray-400 ml-0.5">{{ $p->sparepart->satuan ?? '' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($p->batas_bawah !== null && $p->batas_atas !== null)
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full font-mono whitespace-nowrap">
                                    {{ number_format($p->batas_bawah, 1) }} – {{ number_format($p->batas_atas, 1) }}
                                </span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            @if($p->perlu_restok)
                                <span class="font-bold text-red-600 text-base">+{{ $p->jumlah_restok }}</span>
                                <span class="text-xs text-gray-400 ml-0.5">{{ $p->sparepart->satuan ?? '' }}</span>
                            @else
                                <span class="text-emerald-500 font-semibold">Cukup</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($p->perlu_restok)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    ⚠ Restock
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                    ✅ Aman
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Catatan metodologi --}}
    <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4 text-xs text-purple-700 space-y-1">
        <p class="font-semibold text-purple-800">📌 Cara membaca prediksi ini:</p>
        <ul class="list-disc list-inside space-y-1 text-purple-600">
            <li><strong>Estimasi Bulan Ini</strong> — Prediksi unit yang dibutuhkan khusus pada bulan {{ $bulan->locale('id')->translatedFormat('F Y') }} saja, berdasarkan model Facebook Prophet.</li>
            <li><strong>Total Kumulatif*</strong> — Penjumlahan estimasi dari bulan sekarang ({{ now()->locale('id')->translatedFormat('F Y') }}) sampai bulan yang dipilih, dengan asumsi <strong>tidak ada pembelian/restock</strong> di antaranya. Stok yang ada akan terpakai duluan di bulan-bulan sebelumnya, jadi inilah angka yang sebenarnya menentukan cukup/tidaknya stok — bukan estimasi satu bulan saja.</li>
            <li><strong>Confidence Interval</strong> — Rentang kemungkinan (batas bawah – batas atas) untuk Estimasi Bulan Ini. Semakin sempit, semakin akurat prediksi.</li>
            <li><strong>Rekomendasi Beli & Status</strong> — Dihitung dari <strong>Total Kumulatif</strong> dibandingkan stok saat ini, bukan dari Estimasi Bulan Ini saja.</li>
        </ul>
    </div>

    @else

    {{-- ============================================================ --}}
    {{-- EMPTY STATE                                                    --}}
    {{-- ============================================================ --}}
    @if($adaPrediksiSamaSekali)
        {{-- Sudah ada data prediksi, tapi tidak ada yang match filter saat ini --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-amber-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-6.818a1.5 1.5 0 0 0-.44-1.06L3.13 6.939a3 3 0 0 1-.879-2.121V3.774c0-.897.64-1.683 1.541-1.836Z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="text-gray-700 font-bold text-lg mb-2">Tidak Ada Prediksi untuk Filter Ini</h3>
            <p class="text-gray-400 text-sm max-w-sm mx-auto mb-6">
                @if(request('sparepart_id'))
                    Sparepart yang dipilih belum punya hasil prediksi pada {{ $bulan->locale('id')->translatedFormat('F Y') }}
                    — biasanya karena histori penjualannya masih kurang dari 3 bulan, atau belum pernah terjual sama sekali.
                @else
                    Belum ada hasil prediksi untuk {{ $bulan->locale('id')->translatedFormat('F Y') }}. Coba pilih bulan lain,
                    atau generate ulang dengan rentang bulan yang lebih panjang.
                @endif
            </p>
            <a href="{{ route('admin.predictions.index') }}" class="inline-flex px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-xl transition-colors">
                Reset Filter
            </a>
        </div>
    @else
        {{-- Belum pernah generate prediksi sama sekali --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M1 2.75A.75.75 0 0 1 1.75 2h16.5a.75.75 0 0 1 0 1.5H18v8.75A2.75 2.75 0 0 1 15.25 15h-1.072l.798 3.06a.75.75 0 0 1-1.452.38L13.41 18H6.59l-.114.44a.75.75 0 0 1-1.452-.38L5.823 15H4.75A2.75 2.75 0 0 1 2 12.25V3.5h-.25A.75.75 0 0 1 1 2.75Z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="text-gray-700 font-bold text-lg mb-2">Belum Ada Data Prediksi</h3>
            <p class="text-gray-400 text-sm max-w-sm mx-auto">
                Klik tombol <strong>Generate Prediksi</strong> di pojok kanan atas untuk memanggil prediction-service
                (FastAPI + Prophet) berdasarkan histori penjualan yang sudah tercatat.
            </p>
        </div>
    @endif

    @endif
</div>
@endsection
