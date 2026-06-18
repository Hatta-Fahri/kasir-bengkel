@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('page_title', 'Transaksi Baru')

@section('content')
{{-- Alpine.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div x-data="pos()" x-init="init()" class="h-full text-slate-800 relative bg-slate-50/50 min-h-[80vh] rounded-3xl overflow-hidden">
    
    <form id="form-transaksi" method="POST" action="{{ route('kasir.transactions.store') }}" @submit.prevent="submitForm" class="h-full">
        @csrf

        {{-- ============================================================ --}}
        {{-- 1. LAYAR AWAL: SETUP TRANSAKSI                               --}}
        {{-- ============================================================ --}}
        <div x-show="!isSetupComplete" 
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-slate-50 p-6">
            
            <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-slate-900/20">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 1.39l-1.39 1.39m0 0L21 21m0 0l-1.39-1.39m-2.22-2.22L18 18m0 0l-1.39-1.39m0 0l-1.39 1.39" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Transaksi Baru</h2>
                    <p class="text-sm text-slate-500 mt-1">Pilih jenis transaksi untuk memulai</p>
                </div>
                    
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" x-model="tipe" value="penjualan" class="sr-only peer">
                            <div class="flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-slate-100 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white text-slate-500 transition-all duration-200">
                                <svg class="w-7 h-7 peer-checked:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                <span class="text-sm font-semibold">Penjualan</span>
                                <span class="text-[11px] opacity-60 font-medium -mt-1 text-center">Sparepart saja</span>
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" x-model="tipe" value="servis" class="sr-only peer">
                            <div class="flex flex-col items-center gap-3 p-5 rounded-2xl border-2 border-slate-100 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white text-slate-500 transition-all duration-200">
                                <svg class="w-7 h-7 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.83m-3.703 3.75a3.375 3.375 0 11-4.773-4.773L9.75 7.5M11.42 15.17l-3.95-3.95m5.903 5.903L15 15.75M8.25 15.75l-4.5 4.5M3 16.5l3-3m0 0l-3-3m3 3H9" /></svg>
                                <span class="text-sm font-semibold">Servis</span>
                                <span class="text-[11px] opacity-60 font-medium -mt-1 text-center">Jasa + sparepart</span>
                            </div>
                        </label>
                    </div>

                    {{-- Form Servis Expandable --}}
                    <div x-show="tipe === 'servis'" x-collapse class="space-y-4">
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Plat Nomor <span class="text-red-500">*</span></label>
                                <input x-model="platNomor" type="text" placeholder="B 1234 ABC" class="w-full px-4 py-3 bg-white border-0 rounded-xl focus:ring-2 focus:ring-slate-900 uppercase placeholder:text-slate-300 placeholder:normal-case shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Jenis Kendaraan <span class="text-red-500">*</span></label>
                                <input x-model="jenisMobil" type="text" placeholder="Toyota Avanza" class="w-full px-4 py-3 bg-white border-0 rounded-xl focus:ring-2 focus:ring-slate-900 placeholder:text-slate-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1.5 uppercase tracking-wider">Ongkos Jasa (Rp)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium">Rp</span>
                                    <input x-model.number="ongkosJasa" type="number" min="0" step="1000" class="w-full pl-12 pr-4 py-3 bg-white border-0 rounded-xl focus:ring-2 focus:ring-slate-900 shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" @click="isSetupComplete = true" :disabled="tipe === 'servis' && (!platNomor || !jenisMobil)"
                        class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl transition-all shadow-xl shadow-slate-900/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        Mulai Belanja
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 2. LAYAR UTAMA: KATALOG & PENCARIAN                          --}}
        {{-- ============================================================ --}}
        <div x-show="isSetupComplete" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-500 delay-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="p-6 pb-24 h-full flex flex-col">
            
            {{-- Header Info Transaksi Aktif --}}
            <div class="flex items-center justify-between bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-600">
                        <svg x-show="tipe === 'penjualan'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        <svg x-show="tipe === 'servis'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.83m-3.703 3.75a3.375 3.375 0 11-4.773-4.773L9.75 7.5M11.42 15.17l-3.95-3.95m5.903 5.903L15 15.75M8.25 15.75l-4.5 4.5M3 16.5l3-3m0 0l-3-3m3 3H9" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900" x-text="tipe === 'penjualan' ? 'Penjualan Langsung' : 'Servis Kendaraan'"></h3>
                        <p x-show="tipe === 'servis'" class="text-xs text-slate-500 font-medium" x-text="platNomor.toUpperCase() + ' - ' + jenisMobil"></p>
                        <p x-show="tipe === 'penjualan'" class="text-xs text-slate-500 font-medium">Pembelian Sparepart</p>
                    </div>
                </div>
                <button type="button" @click="isSetupComplete = false" class="text-sm font-semibold text-slate-400 hover:text-slate-900 transition-colors flex items-center gap-1.5 px-3 py-1.5 rounded-lg hover:bg-slate-50">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                    Ubah
                </button>
            </div>

            {{-- Area Pencarian Produk --}}
            <div class="flex-1 max-w-4xl mx-auto w-full">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight text-center">Tambahkan Produk</h2>
                
                <div class="relative max-w-2xl mx-auto mb-8 shadow-2xl shadow-slate-200/50 rounded-2xl">
                    <svg class="w-6 h-6 absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input x-model="searchSp" type="text" placeholder="Ketik nama atau kode sparepart..."
                        class="w-full pl-14 pr-6 py-4 text-base border-0 ring-1 ring-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-slate-900 transition-all placeholder:text-slate-400 bg-white">
                </div>

                {{-- Hasil Pencarian (Grid) --}}
                <div x-show="searchSp.length > 0 && filteredSp.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="sp in filteredSp" :key="sp.id">
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between hover:border-slate-300 hover:shadow-md transition-all group">
                            <div>
                                <p class="text-sm font-bold text-slate-900" x-text="sp.nama"></p>
                                <p class="text-xs text-slate-400 mt-1" x-text="sp.kode + ' · Stok: ' + sp.stok + ' ' + sp.satuan"></p>
                                <p class="text-sm font-bold text-slate-900 mt-2" x-text="'Rp ' + formatRupiah(sp.harga_jual)"></p>
                            </div>
                            <button type="button" @click="addToCart(sp)" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            </button>
                        </div>
                    </template>
                </div>

                <div x-show="searchSp.length > 0 && filteredSp.length === 0" class="text-center py-10">
                    <p class="text-slate-400">Produk tidak ditemukan atau stok habis.</p>
                </div>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- 3. FLOATING CART BUTTON                                      --}}
        {{-- ============================================================ --}}
        <button x-show="isSetupComplete" type="button" @click="isCartOpen = true" 
            class="fixed bottom-8 right-8 z-30 bg-slate-900 text-white rounded-full p-4 shadow-2xl shadow-slate-900/40 hover:scale-105 active:scale-95 transition-all flex items-center gap-3 pr-6 group">
            <div class="relative">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                <span x-show="cart.length > 0" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-slate-900" x-text="cart.length"></span>
            </div>
            <span class="font-semibold text-sm">Lihat Keranjang</span>
        </button>

        {{-- ============================================================ --}}
        {{-- 4. DRAWER SLIDE-OVER: KERANJANG & PEMBAYARAN                 --}}
        {{-- ============================================================ --}}
        <div x-show="isCartOpen" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="isCartOpen" 
                 x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isCartOpen = false"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                {{-- Panel --}}
                <div x-show="isCartOpen" 
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     class="pointer-events-auto w-screen max-w-md">
                    
                    <div class="flex h-full flex-col bg-white shadow-2xl">
                        {{-- Header Drawer --}}
                        <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100 bg-white">
                            <h2 class="text-lg font-bold text-slate-900 tracking-tight" id="slide-over-title">Keranjang Belanja</h2>
                            <button type="button" @click="isCartOpen = false" class="rounded-xl p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                <span class="sr-only">Close panel</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        {{-- Error Global --}}
                        @if(session('error'))
                        <div class="px-6 py-3 bg-red-50 border-b border-red-100 text-red-700 text-sm flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ session('error') }}
                        </div>
                        @endif

                        {{-- Isi Keranjang --}}
                        <div class="flex-1 overflow-y-auto px-6 py-4">
                            <div x-show="cart.length === 0" class="flex flex-col items-center justify-center h-full opacity-50">
                                <svg class="w-16 h-16 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                                <p class="text-sm font-medium">Keranjang masih kosong</p>
                            </div>

                            <ul role="list" class="divide-y divide-slate-100" x-show="cart.length > 0">
                                <template x-for="(item, index) in cart" :key="item.id">
                                    <li class="flex py-4">
                                        <div class="ml-4 flex flex-1 flex-col justify-between">
                                            <div>
                                                <div class="flex justify-between text-sm font-bold text-slate-900">
                                                    <h3 class="truncate w-40" x-text="item.nama"></h3>
                                                    <p class="ml-4" x-text="'Rp ' + formatRupiah(item.qty * item.harga_jual)"></p>
                                                </div>
                                                <p class="mt-1 text-xs text-slate-500 font-medium" x-text="'@ Rp ' + formatRupiah(item.harga_jual) + ' / ' + item.satuan"></p>
                                            </div>
                                            <div class="flex flex-1 items-end justify-between mt-4">
                                                <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-lg p-1">
                                                    <button type="button" @click="decreaseQty(index)" class="w-6 h-6 rounded bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-slate-900">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" /></svg>
                                                    </button>
                                                    <input type="number" x-model.number="item.qty" min="1" :max="item.stok" @change="item.qty = Math.min(Math.max(1, item.qty), item.stok)"
                                                        class="w-10 text-center text-xs font-bold border-none bg-transparent p-0 focus:ring-0">
                                                    <button type="button" @click="increaseQty(index)" class="w-6 h-6 rounded bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-slate-900">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                    </button>
                                                </div>
                                                <button type="button" @click="removeFromCart(index)" class="text-xs font-bold text-red-500 hover:text-red-700">Hapus</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>

                                {{-- Baris Ongkos Jasa (hanya servis & ongkos > 0) --}}
                                <li x-show="tipe === 'servis' && ongkosJasa > 0" class="flex py-4 border-t border-slate-100">
                                    <div class="ml-4 flex flex-1 items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.83m-3.703 3.75a3.375 3.375 0 11-4.773-4.773L9.75 7.5M11.42 15.17l-3.95-3.95m5.903 5.903L15 15.75M8.25 15.75l-4.5 4.5M3 16.5l3-3m0 0l-3-3m3 3H9" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">Ongkos Jasa</p>
                                                <p class="text-xs text-slate-500 font-medium mt-0.5" x-text="platNomor.toUpperCase() + ' · ' + jenisMobil"></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold text-slate-900" x-text="'Rp ' + formatRupiah(ongkosJasa)"></p>
                                            <button type="button" @click="isCartOpen = false; isSetupComplete = false"
                                                class="text-xs text-blue-500 hover:text-blue-700 font-semibold mt-0.5">Ubah</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            {{-- Catatan (Muncul jika ada item di keranjang) --}}
                            <div x-show="cart.length > 0" class="mt-6 pt-6 border-t border-slate-100">
                                <label class="block text-xs font-semibold text-slate-500 mb-2 uppercase tracking-wider">Catatan Tambahan</label>
                                <textarea x-model="catatan" rows="2" placeholder="Tulis catatan jika ada..."
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-slate-900 resize-none placeholder:text-slate-400"></textarea>
                            </div>
                        </div>

                        {{-- Footer Pembayaran --}}
                        <div class="border-t border-slate-200 bg-slate-50 px-6 py-6">
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm text-slate-500 font-medium">
                                    <p>Subtotal Produk</p>
                                    <p x-text="'Rp ' + formatRupiah(subtotalSparepart)"></p>
                                </div>
                                <div x-show="tipe === 'servis'" class="flex justify-between text-sm text-slate-500 font-medium">
                                    <p>Ongkos Jasa</p>
                                    <p x-text="'Rp ' + formatRupiah(ongkosJasa)"></p>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-slate-900 pt-3 border-t border-slate-200">
                                    <p>Total Tagihan</p>
                                    <p x-text="'Rp ' + formatRupiah(totalBayar)"></p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="metode" value="cash" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-sm font-semibold text-slate-500">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V5.988c0-.754-.726-1.294-1.453-1.096V5.988a60.224 60.224 0 01-15.797 2.101c-.727.198-1.453-.342-1.453-1.096V18.75z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            Tunai
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" x-model="metode" value="qris" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 py-3 rounded-xl border-2 border-slate-200 bg-white peer-checked:border-slate-900 peer-checked:bg-slate-900 peer-checked:text-white transition-all text-sm font-semibold text-slate-500">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h1.5v-1.5H16.5v1.5h1.5v-1.5h1.5V19.5m-4.5-3h.75v.75h-.75v-.75z" /></svg>
                                            QRIS
                                        </div>
                                    </label>
                                </div>

                                <div x-show="metode === 'cash'" x-collapse>
                                    <div class="space-y-3 pt-2">
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-500 font-medium">Rp</span>
                                            <input x-model.number="uangDiterima" type="number" min="0" step="1000" placeholder="Uang Diterima"
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

                                <button type="submit" :disabled="!canSubmit"
                                    :class="canSubmit ? 'bg-slate-900 hover:bg-slate-800 text-white shadow-xl shadow-slate-900/20' : 'bg-slate-200 text-slate-400 cursor-not-allowed'"
                                    class="w-full mt-4 py-4 font-bold rounded-xl transition-all active:scale-[0.98] text-sm flex items-center justify-center gap-2">
                                    <span x-show="!loading">Proses & Cetak</span>
                                    <span x-show="loading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Memproses...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden inputs yang dikirim ke server --}}
        <input type="hidden" name="tipe_transaksi" :value="tipe">
        <input type="hidden" name="plat_nomor" :value="platNomor">
        <input type="hidden" name="jenis_mobil" :value="jenisMobil">
        <input type="hidden" name="ongkos_jasa" :value="ongkosJasa">
        <input type="hidden" name="metode_pembayaran" :value="metode">
        <input type="hidden" name="uang_diterima" :value="metode === 'cash' ? uangDiterima : null">
        <input type="hidden" name="catatan" :value="catatan">

        {{-- Hidden inputs array items --}}
        <template x-for="(item, index) in cart" :key="item.id">
            <div>
                <input type="hidden" :name="'items[' + index + '][sparepart_id]'" :value="item.id">
                <input type="hidden" :name="'items[' + index + '][qty]'" :value="item.qty">
            </div>
        </template>
    </form>
</div>

@push('scripts')
{{-- Tambahkan x-collapse plugin dari Alpine --}}
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script>
function pos() {
    return {
        // State UI Baru
        isSetupComplete: false,
        isCartOpen: false,

        // State Data
        tipe:        'penjualan',
        platNomor:   '',
        jenisMobil:  '',
        ongkosJasa:  0,
        metode:      'cash',
        uangDiterima: 0,
        catatan:     '',
        searchSp:    '',
        cart:        [],
        loading:     false,

        allSpareparts: [],

        init() {
            this.allSpareparts = JSON.parse(document.getElementById('spareparts-data').textContent);
            
            // Watcher untuk otomatis buka form pembayaran tunai saat total bayar dihitung
            this.$watch('totalBayar', (val) => {
                if (this.metode === 'cash' && this.uangDiterima < val) {
                    this.uangDiterima = val; 
                }
            });
        },

        get filteredSp() {
            const q = this.searchSp.toLowerCase();
            return this.allSpareparts.filter(sp =>
                (sp.nama.toLowerCase().includes(q) || sp.kode.toLowerCase().includes(q))
                && sp.stok > 0
                && !this.cart.find(c => c.id === sp.id)
            );
        },

        addToCart(sp) {
            this.cart.push({ ...sp, qty: 1 });
            this.searchSp = '';
            // Auto-open cart jika di layar kecil bisa diaktifkan, tapi untuk desktop lebih baik diam.
        },

        removeFromCart(index) { this.cart.splice(index, 1); },
        increaseQty(index) { if (this.cart[index].qty < this.cart[index].stok) this.cart[index].qty++; },
        decreaseQty(index) { if (this.cart[index].qty > 1) this.cart[index].qty--; else this.removeFromCart(index); },

        get subtotalSparepart() { return this.cart.reduce((s, i) => s + i.qty * i.harga_jual, 0); },
        get totalBayar() { return this.subtotalSparepart + (this.tipe === 'servis' ? Number(this.ongkosJasa) : 0); },
        get kembalian() { return this.uangDiterima - this.totalBayar; },

        get quickNominals() {
            const t = this.totalBayar;
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
            if (this.loading) return false;
            if (this.totalBayar <= 0) return false;
            if (this.tipe === 'servis' && (!this.platNomor || !this.jenisMobil)) return false;
            if (this.metode === 'cash' && (this.uangDiterima < this.totalBayar)) return false;
            return true;
        },

        formatRupiah(n) {
            return Math.round(n).toLocaleString('id-ID');
        },

        submitForm() {
            if (!this.canSubmit) return;
            this.loading = true;
            document.getElementById('form-transaksi').submit();
        },
    };
}
</script>
<script id="spareparts-data" type="application/json">
    {!! $sparepartsJson !!}
</script>
@endpush
@endsection 