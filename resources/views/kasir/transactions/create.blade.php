@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('page_title', 'Transaksi Baru')

@section('content')
{{-- Alpine.js via CDN --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div x-data="pos()" x-init="init()" class="h-full">
    <form id="form-transaksi" method="POST" action="{{ route('kasir.transactions.store') }}" @submit.prevent="submitForm">
        @csrf

        <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

            {{-- ============================================================ --}}
            {{-- PANEL KIRI: Info Pelanggan + Pilih Sparepart                 --}}
            {{-- ============================================================ --}}
            <div class="xl:col-span-2 space-y-4">

                {{-- Tipe Transaksi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Tipe Transaksi</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="tipe" value="penjualan" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                <svg class="w-6 h-6 text-gray-400 peer-checked:text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25Z"/></svg>
                                <span class="text-xs font-semibold text-gray-600">Penjualan</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="tipe" value="servis" class="sr-only peer">
                            <div class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.641l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.306Z" clip-rule="evenodd"/></svg>
                                <span class="text-xs font-semibold text-gray-600">Servis</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Info Kendaraan (hanya jika servis) --}}
                <div x-show="tipe === 'servis'" x-transition class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Data Kendaraan</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Plat Nomor <span class="text-red-500">*</span></label>
                            <input x-model="platNomor" type="text" placeholder="Contoh: B 1234 ABC"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 uppercase"
                                style="text-transform:uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Mobil <span class="text-red-500">*</span></label>
                            <input x-model="jenisMobil" type="text" placeholder="Contoh: Toyota Avanza 2019"
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Ongkos Jasa (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">Rp</span>
                                <input x-model.number="ongkosJasa" type="number" min="0" step="1000" placeholder="0"
                                    class="w-full pl-8 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pilih Sparepart --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Tambah Sparepart</h3>

                    {{-- Search / Select Sparepart --}}
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Cari Sparepart</label>
                            <input x-model="searchSp" type="text" placeholder="Ketik nama atau kode sparepart..."
                                class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                        </div>

                        {{-- Dropdown hasil pencarian --}}
                        <div x-show="searchSp.length > 0 && filteredSp.length > 0"
                             class="border border-gray-200 rounded-xl overflow-hidden max-h-48 overflow-y-auto divide-y divide-gray-50">
                            <template x-for="sp in filteredSp" :key="sp.id">
                                <button type="button" @click="addToCart(sp)"
                                    class="w-full flex items-center justify-between px-3 py-2.5 hover:bg-amber-50 transition-colors text-left">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800" x-text="sp.nama"></p>
                                        <p class="text-xs text-gray-400" x-text="sp.kode + ' · Stok: ' + sp.stok + ' ' + sp.satuan"></p>
                                    </div>
                                    <p class="text-xs font-bold text-emerald-600 ml-2 whitespace-nowrap"
                                       x-text="'Rp ' + formatRupiah(sp.harga_jual)"></p>
                                </button>
                            </template>
                        </div>

                        <p x-show="searchSp.length > 0 && filteredSp.length === 0"
                           class="text-xs text-gray-400 text-center py-2">Sparepart tidak ditemukan atau stok habis.</p>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan (opsional)</label>
                    <textarea x-model="catatan" rows="2" placeholder="Catatan transaksi..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- PANEL KANAN: Keranjang + Pembayaran                          --}}
            {{-- ============================================================ --}}
            <div class="xl:col-span-3 space-y-4">

                {{-- Error global --}}
                @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-3.536-9.536a.75.75 0 0 0-1.06 1.061L8.94 10l-1.537 1.536a.75.75 0 1 0 1.06 1.06L10 11.06l1.536 1.537a.75.75 0 1 0 1.06-1.061L11.061 10l1.537-1.536a.75.75 0 0 0-1.06-1.06L10 8.939 8.464 7.403Z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
                @endif

                {{-- Keranjang --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-700">Keranjang Belanja</h3>
                        <span class="text-xs text-gray-400" x-text="cart.length + ' item'"></span>
                    </div>

                    {{-- Empty state --}}
                    <div x-show="cart.length === 0" class="py-10 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 2.25a.75.75 0 0 0 0 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 0 0-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 0 0 0-1.5H5.378A2.25 2.25 0 0 1 7.5 15h11.218a.75.75 0 0 0 .674-.421 60.358 60.358 0 0 0 2.96-7.228.75.75 0 0 0-.525-.965A60.864 60.864 0 0 0 5.68 4.509l-.232-.867A1.875 1.875 0 0 0 3.636 2.25H2.25Z"/></svg>
                        <p class="text-sm text-gray-300">Keranjang kosong</p>
                        <p class="text-xs text-gray-300 mt-1">Pilih sparepart dari panel kiri</p>
                    </div>

                    {{-- Item list --}}
                    <div x-show="cart.length > 0" class="divide-y divide-gray-50">
                        <template x-for="(item, index) in cart" :key="item.id">
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate" x-text="item.nama"></p>
                                    <p class="text-xs text-emerald-600 font-medium"
                                       x-text="'@ Rp ' + formatRupiah(item.harga_jual) + ' / ' + item.satuan"></p>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <button type="button" @click="decreaseQty(index)"
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd"/></svg>
                                    </button>
                                    <input type="number" x-model.number="item.qty" min="1" :max="item.stok"
                                        @change="item.qty = Math.min(Math.max(1, item.qty), item.stok)"
                                        class="w-12 text-center text-sm font-bold border border-gray-200 rounded-lg py-1 focus:outline-none focus:ring-1 focus:ring-amber-400">
                                    <button type="button" @click="increaseQty(index)"
                                        class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 transition-colors">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"/></svg>
                                    </button>
                                </div>
                                <p class="text-sm font-bold text-gray-800 w-24 text-right flex-shrink-0"
                                   x-text="'Rp ' + formatRupiah(item.qty * item.harga_jual)"></p>
                                <button type="button" @click="removeFromCart(index)"
                                    class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-400 transition-colors flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Ringkasan Pembayaran --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
                    <h3 class="text-sm font-bold text-gray-700">Ringkasan Pembayaran</h3>

                    {{-- Subtotals --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal Sparepart</span>
                            <span x-text="'Rp ' + formatRupiah(subtotalSparepart)"></span>
                        </div>
                        <div x-show="tipe === 'servis'" class="flex justify-between text-gray-500">
                            <span>Ongkos Jasa</span>
                            <span x-text="'Rp ' + formatRupiah(ongkosJasa)"></span>
                        </div>
                        <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-100">
                            <span>Total Bayar</span>
                            <span class="text-amber-600" x-text="'Rp ' + formatRupiah(totalBayar)"></span>
                        </div>
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" x-model="metode" value="cash" class="sr-only peer">
                                <div class="flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all text-sm font-medium text-gray-600 peer-checked:text-amber-700">
                                    💵 Cash
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" x-model="metode" value="qris" class="sr-only peer">
                                <div class="flex items-center justify-center gap-2 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all text-sm font-medium text-gray-600 peer-checked:text-purple-700">
                                    📱 QRIS
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Input Uang Diterima (hanya Cash) --}}
                    <div x-show="metode === 'cash'" x-transition class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Uang Diterima (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">Rp</span>
                                <input x-model.number="uangDiterima" type="number" min="0" step="1000" placeholder="0"
                                    class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400">
                            </div>
                        </div>
                        {{-- Kembalian --}}
                        <div :class="kembalian < 0 ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200'"
                             class="flex justify-between items-center px-4 py-3 rounded-xl border">
                            <span class="text-sm font-medium" :class="kembalian < 0 ? 'text-red-700' : 'text-emerald-700'">Kembalian</span>
                            <span class="font-bold" :class="kembalian < 0 ? 'text-red-700' : 'text-emerald-700'"
                                  x-text="kembalian < 0 ? '⚠ Kurang Rp ' + formatRupiah(Math.abs(kembalian)) : 'Rp ' + formatRupiah(kembalian)"></span>
                        </div>
                        {{-- Tombol nominal cepat --}}
                        <div class="grid grid-cols-3 gap-2">
                            <template x-for="nominal in quickNominals">
                                <button type="button" @click="uangDiterima = nominal"
                                    class="py-1.5 text-xs font-medium border border-gray-200 rounded-lg hover:border-amber-400 hover:bg-amber-50 transition-colors"
                                    x-text="'Rp ' + formatRupiah(nominal)"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Tombol Proses --}}
                    <button type="submit" :disabled="!canSubmit"
                        :class="canSubmit
                            ? 'bg-amber-500 hover:bg-amber-600 shadow shadow-amber-500/30 cursor-pointer'
                            : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="w-full py-3 text-white font-bold rounded-xl transition-all active:scale-95 text-sm">
                        <span x-show="!loading">🖨 Proses & Cetak Struk</span>
                        <span x-show="loading">Memproses...</span>
                    </button>
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
<script>
function pos() {
    return {
        // State
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

        // Data sparepart dari server
        allSpareparts: [],

        // Init: parse JSON dari Blade
        init() {
            this.allSpareparts = JSON.parse(document.getElementById('spareparts-data').textContent);
        },

        // Sparepart yang difilter berdasarkan pencarian
        get filteredSp() {
            const q = this.searchSp.toLowerCase();
            return this.allSpareparts.filter(sp =>
                (sp.nama.toLowerCase().includes(q) || sp.kode.toLowerCase().includes(q))
                && sp.stok > 0
                && !this.cart.find(c => c.id === sp.id) // sembunyikan yang sudah di keranjang
            );
        },

        // Tambah ke keranjang
        addToCart(sp) {
            this.cart.push({ ...sp, qty: 1 });
            this.searchSp = '';
        },

        removeFromCart(index) { this.cart.splice(index, 1); },
        increaseQty(index) { if (this.cart[index].qty < this.cart[index].stok) this.cart[index].qty++; },
        decreaseQty(index) { if (this.cart[index].qty > 1) this.cart[index].qty--; else this.removeFromCart(index); },

        // Kalkulasi
        get subtotalSparepart() { return this.cart.reduce((s, i) => s + i.qty * i.harga_jual, 0); },
        get totalBayar() { return this.subtotalSparepart + (this.tipe === 'servis' ? Number(this.ongkosJasa) : 0); },
        get kembalian() { return this.uangDiterima - this.totalBayar; },

        // Nominal cepat berdasarkan total bayar
        get quickNominals() {
            const t   = this.totalBayar;
            const set = new Set();
            [50000, 100000, 200000, 50000].forEach(d => {
                const v = Math.ceil(t / d) * d;
                if (v > 0) set.add(v);
            });
            set.add(Math.ceil(t / 100000) * 100000 + 100000);
            return [...set].sort((a, b) => a - b).slice(0, 6);
        },

        get canSubmit() {
            if (this.loading) return false;
            if (this.totalBayar <= 0) return false;
            if (this.tipe === 'servis' && (!this.platNomor || !this.jenisMobil)) return false;
            if (this.metode === 'cash' && (this.uangDiterima < this.totalBayar)) return false;
            return true;
        },

        // Format Rupiah
        formatRupiah(n) {
            return Math.round(n).toLocaleString('id-ID');
        },

        // Submit form
        submitForm() {
            if (!this.canSubmit) return;
            this.loading = true;
            document.getElementById('form-transaksi').submit();
        },
    };
}
</script>
{{-- JSON data sparepart --}}
<script id="spareparts-data" type="application/json">
    {!! $sparepartsJson !!}
</script>
@endpush
@endsection
