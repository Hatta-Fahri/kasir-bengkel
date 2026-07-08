@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6 text-slate-800" x-data="transactionIndex()">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Transaksi Saya</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Semua transaksi yang telah Anda kerjakan</p>
        </div>
        <a href="{{ route('kasir.transactions.create') }}"
           class="inline-flex items-center gap-2 px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-2xl shadow-xl shadow-slate-900/20 transition-all active:scale-[0.98]">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Transaksi Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-2xl px-5 py-4 text-sm text-green-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700 font-medium">
        <svg class="w-5 h-5 flex-shrink-0 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-1-5a1 1 0 1 0 2 0V9a1 1 0 1 0-2 0v4Zm1-7a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-5">
        <form method="GET" action="{{ route('kasir.transactions.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                    class="pl-4 pr-4 py-3 text-sm bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 text-slate-700 font-medium transition-shadow">
            </div>
            <div class="relative">
                <select name="tipe" class="pl-4 pr-10 py-3 text-sm bg-slate-50 border-0 ring-1 ring-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900 text-slate-700 font-medium transition-shadow appearance-none">
                    <option value="">Semua Tipe Transaksi</option>
                    <option value="penjualan" {{ request('tipe') === 'penjualan' ? 'selected' : '' }}>Penjualan</option>
                    <option value="servis" {{ request('tipe') === 'servis' ? 'selected' : '' }}>Servis</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-slate-900/10">Filter Data</button>
            
            @if(request('tanggal') || request('tipe'))
                <a href="{{ route('kasir.transactions.index') }}" class="px-6 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-colors">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/80 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">No. Struk</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Info Kendaraan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right whitespace-nowrap">Total Bayar</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $trx)
                    <tr class="hover:bg-slate-50/70 transition-colors group">
                        {{-- No Struk --}}
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                            {{ $trx->no_struk }}
                        </td>
                        
                        {{-- Tipe --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($trx->tipe_transaksi === 'servis')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-700 shadow-sm">
                                    Servis
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white shadow-sm">
                                    Penjualan
                                </span>
                            @endif
                        </td>
                        
                        {{-- Info Kendaraan --}}
                        <td class="px-6 py-4">
                            @if($trx->tipe_transaksi === 'servis')
                                <p class="font-bold text-slate-900">{{ $trx->jenis_mobil }}</p>
                                <p class="font-mono text-xs text-slate-500 font-medium mt-0.5">{{ strtoupper($trx->plat_nomor) }}</p>
                            @else
                                <span class="text-slate-300 font-bold">—</span>
                            @endif
                        </td>
                        
                        {{-- Total Bayar --}}
                        <td class="px-6 py-4 text-right font-bold text-slate-900 tracking-tight whitespace-nowrap">
                            Rp {{ number_format($trx->total_bayar, 0, ',', '.') }}
                        </td>
                        
                        {{-- Metode & Status --}}
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $trx->badge_status }}">
                                {{ $trx->label_status }}
                            </span>
                        </td>
                        
                        {{-- Waktu --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-sm font-bold text-slate-900">{{ $trx->created_at->locale('id')->translatedFormat('d M Y') }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ $trx->created_at->format('H:i') }} WIB</p>
                        </td>
                        
                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2 flex-wrap">

                                @if($trx->tipe_transaksi === 'penjualan')
                                    {{-- Penjualan: pembayaran (termasuk Xendit) sudah dituntaskan di halaman Transaksi Baru, jadi cukup cetak struk --}}
                                    <a href="{{ route('kasir.transactions.receipt', $trx->id) }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-slate-400 hover:bg-slate-50 rounded-xl transition-all shadow-sm whitespace-nowrap">
                                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .121.08.232.198.256 3.425.688 6.945.688 10.404 0 .118-.024.198-.135.198-.256V7.03z" /></svg>
                                        Cetak Struk
                                    </a>
                                @else
                                    {{-- Servis --}}

                                    {{-- Estimasi: Setujui & Batalkan --}}
                                    @if($trx->status === 'estimasi')
                                        <form method="POST" action="{{ route('kasir.transactions.approve', $trx->id) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-green-700 bg-green-50 hover:bg-green-100 border border-green-200 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                                Setujui
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('kasir.transactions.cancel', $trx->id) }}"
                                              onsubmit="return confirm('Batalkan estimasi {{ $trx->no_struk }}?')">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                                Tolak
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Proses: Bayar via Xendit (+ Cetak Struk) jika invoice sudah ada, atau Servis Selesai untuk memilih metode pembayaran --}}
                                    @if($trx->status === 'proses')
                                        @if($trx->payment_url)
                                            <a href="{{ $trx->payment_url }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                                Bayar via Xendit
                                            </a>
                                            <a href="{{ route('kasir.transactions.receipt', $trx->id) }}"
                                               class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-slate-400 hover:bg-slate-50 rounded-xl transition-all shadow-sm whitespace-nowrap">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .121.08.232.198.256 3.425.688 6.945.688 10.404 0 .118-.024.198-.135.198-.256V7.03z" /></svg>
                                                Cetak Struk
                                            </a>
                                        @else
                                            <button type="button" @click="openPaymentModal({{ $trx->id }}, {{ $trx->total_bayar }}, '{{ $trx->no_struk }}')" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75" /></svg>
                                                Servis Selesai
                                            </button>
                                        @endif
                                    @endif

                                    {{-- Struk (hanya yang sudah selesai) --}}
                                    @if($trx->status === 'selesai')
                                        <a href="{{ route('kasir.transactions.receipt', $trx->id) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:border-slate-400 hover:bg-slate-50 rounded-xl transition-all shadow-sm whitespace-nowrap">
                                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0v2.796c0 .121.08.232.198.256 3.425.688 6.945.688 10.404 0 .118-.024.198-.135.198-.256V7.03z" /></svg>
                                            Cetak Struk
                                        </a>
                                    @endif

                                    @if($trx->status === 'batal')
                                        <span class="text-xs text-slate-400 italic">Dibatalkan</span>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            </div>
                            <p class="text-slate-500 font-medium">Belum ada transaksi ditemukan</p>
                            <a href="{{ route('kasir.transactions.create') }}" class="text-slate-900 text-sm font-bold hover:underline mt-2 inline-block">Mulai transaksi baru &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Pembayaran --}}
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" @click="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6">
                
                <form :action="'/kasir/transactions/' + selectedTrxId + '/complete'" method="POST">
                    @csrf
                    <div>
                        <div class="flex items-center justify-center w-12 h-12 mx-auto bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Selesaikan Pembayaran</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500">Tagihan untuk struk <strong class="text-slate-900" x-text="selectedTrxNo"></strong></p>
                                <p class="text-2xl font-black text-slate-900 mt-2" x-text="'Rp ' + formatRupiah(selectedTrxTotal)"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="grid grid-cols-3 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" x-model="metode" value="cash" class="sr-only peer">
                                <div class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-sm font-semibold text-slate-500">
                                    Tunai
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" x-model="metode" value="qris" class="sr-only peer">
                                <div class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-sm font-semibold text-slate-500">
                                    QRIS
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="metode_pembayaran" x-model="metode" value="xendit" class="sr-only peer">
                                <div class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-sm font-semibold text-slate-500">
                                    Xendit
                                </div>
                            </label>
                        </div>

                        <div x-show="metode === 'cash'" class="space-y-3">
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-500 font-medium">Rp</span>
                                <input x-model.number="uangDiterima" name="uang_diterima" type="number" min="0" step="1000" placeholder="Uang Diterima"
                                    class="w-full pl-12 pr-4 py-4 text-base font-bold bg-white border-0 ring-1 ring-slate-200 rounded-xl focus:ring-2 focus:ring-slate-900 shadow-sm">
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="nominal in quickNominals">
                                    <button type="button" @click="uangDiterima = nominal"
                                        class="py-2 text-xs font-semibold bg-white border border-slate-200 rounded-lg text-slate-600 hover:border-slate-900 hover:text-slate-900 transition-colors shadow-sm"
                                        x-text="formatRupiah(nominal)"></button>
                                </template>
                            </div>
                            <div :class="kembalian < 0 ? 'bg-red-50 text-red-700 ring-red-100' : 'bg-slate-900 text-white ring-slate-900'" class="flex justify-between items-center px-4 py-3 rounded-xl ring-1 mt-2">
                                <span class="text-sm font-semibold">Kembalian</span>
                                <span class="font-bold tracking-tight">
                                    <span x-show="kembalian < 0">Kurang Rp <span x-text="formatRupiah(Math.abs(kembalian))"></span></span>
                                    <span x-show="kembalian >= 0" x-text="'Rp ' + formatRupiah(kembalian)"></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="submit" :disabled="!canSubmit"
                            :class="canSubmit ? 'bg-purple-600 hover:bg-purple-700 text-white shadow-purple-600/20 shadow-lg' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                            class="inline-flex justify-center w-full px-4 py-3 text-sm font-bold border border-transparent rounded-xl focus:outline-none transition-all sm:w-auto"
                            x-text="metode === 'xendit' ? 'Buat Invoice Xendit' : 'Selesaikan & Cetak'">
                        </button>
                        <button type="button" @click="closeModal()" class="inline-flex justify-center w-full px-4 py-3 mt-3 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 focus:outline-none transition-all sm:mt-0 sm:w-auto">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Alpine.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function transactionIndex() {
    return {
        showModal: false,
        selectedTrxId: null,
        selectedTrxNo: '',
        selectedTrxTotal: 0,
        metode: 'cash',
        uangDiterima: 0,

        openPaymentModal(id, total, noStruk) {
            this.selectedTrxId = id;
            this.selectedTrxTotal = total;
            this.selectedTrxNo = noStruk;
            this.metode = 'cash';
            this.uangDiterima = total;
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
        },

        get kembalian() {
            return this.uangDiterima - this.selectedTrxTotal;
        },

        get quickNominals() {
            const t = this.selectedTrxTotal;
            if(t === 0) return [];
            const set = new Set();
            [50000, 100000].forEach(d => {
                const v = Math.ceil(t / d) * d;
                if (v > 0) set.add(v);
            });
            set.add(Math.ceil(t / 100000) * 100000 + 50000);
            set.add(Math.ceil(t / 100000) * 100000 + 100000);
            return [...set].sort((a, b) => a - b).filter(n => n >= t).slice(0, 3);
        },

        get canSubmit() {
            if (this.metode === 'cash' && this.uangDiterima < this.selectedTrxTotal) return false;
            return true;
        },

        formatRupiah(n) {
            return Math.round(n).toLocaleString('id-ID');
        }
    }
}
</script>
@endpush
@endsection