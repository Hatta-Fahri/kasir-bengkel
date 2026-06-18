@extends('layouts.app')

@section('title', 'Struk ' . $transaction->no_struk)
@section('page_title', 'Struk Transaksi')

@section('content')
    <div class="max-w-lg mx-auto space-y-4">

        {{-- Action buttons --}}
        <div class="flex items-center justify-between flex-wrap gap-3">
            <a href="{{ route('kasir.transactions.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z"
                        clip-rule="evenodd" />
                </svg>
                Transaksi Baru
            </a>
            <button onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow shadow-amber-500/30 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.552c.377.046.752.097 1.126.153C17.99 6.924 19 8.091 19 9.473v5.277A2.25 2.25 0 0 1 16.75 17h-.5v.25A1.75 1.75 0 0 1 14.5 19h-9a1.75 1.75 0 0 1-1.75-1.75V17h-.5A2.25 2.25 0 0 1 1 14.75V9.473c0-1.382 1.01-2.549 2.374-2.768.374-.056.75-.107 1.126-.153V2.75Zm1.5 0v3.301l6 .003V2.75a.25.25 0 0 0-.25-.25h-5.5a.25.25 0 0 0-.25.25Zm6.75 8.5a.75.75 0 0 0 0 1.5h.5a.75.75 0 0 0 0-1.5h-.5Z"
                        clip-rule="evenodd" />
                </svg>
                Cetak Struk
            </button>
        </div>

        {{-- Struk --}}
        <div id="print-area" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            {{-- Header struk --}}
            <div class="text-center mb-5 pb-4 border-b border-dashed border-gray-200">
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.641l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.306Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h1 class="font-bold text-gray-800 text-lg">BENGKEL BOS AIR CONDITIONER</h1>
                <p class="text-gray-400 text-xs">Sistem Kasir Bengkel</p>
                <p class="font-mono font-bold text-amber-600 text-sm mt-2">{{ $transaction->no_struk }}</p>
            </div>

            {{-- Info transaksi --}}
            <div class="space-y-1.5 text-xs mb-4">
                <div class="flex justify-between text-gray-500">
                    <span>Tanggal</span>
                    <span
                        class="font-medium text-gray-700">{{ $transaction->created_at->locale('id')->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Kasir</span>
                    <span class="font-medium text-gray-700">{{ $transaction->kasir->name }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Tipe</span>
                    <span
                        class="font-medium capitalize {{ $transaction->tipe_transaksi === 'servis' ? 'text-blue-600' : 'text-amber-600' }}">
                        {{ $transaction->tipe_transaksi }}
                    </span>
                </div>
                @if ($transaction->tipe_transaksi === 'servis')
                    <div class="flex justify-between text-gray-500">
                        <span>Kendaraan</span>
                        <span class="font-medium text-gray-700">{{ $transaction->jenis_mobil }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Plat Nomor</span>
                        <span class="font-mono font-bold text-gray-800">{{ strtoupper($transaction->plat_nomor) }}</span>
                    </div>
                @endif
            </div>

            {{-- Detail sparepart --}}
            @if ($transaction->details->count() > 0)
                <div class="border-t border-dashed border-gray-200 pt-3 mb-3">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Sparepart</p>
                    <div class="space-y-2">
                        @foreach ($transaction->details as $detail)
                            <div class="flex justify-between items-start text-xs">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $detail->sparepart->nama_part }}</p>
                                    <p class="text-gray-400">{{ $detail->qty }} x Rp
                                        {{ number_format($detail->harga_jual_saat_transaksi, 0, ',', '.') }}</p>
                                </div>
                                <span class="font-semibold text-gray-700 ml-2">Rp
                                    {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Summary --}}
            <div class="border-t border-dashed border-gray-200 pt-3 space-y-1.5 text-xs">
                @if ($transaction->subtotal_sparepart > 0)
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Sparepart</span>
                        <span>Rp {{ number_format($transaction->subtotal_sparepart, 0, ',', '.') }}</span>
                    </div>
                @endif
                @if ($transaction->ongkos_jasa > 0)
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Jasa</span>
                        <span>Rp {{ number_format($transaction->ongkos_jasa, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold text-sm text-gray-800 pt-1.5 border-t border-gray-100">
                    <span>Total Bayar</span>
                    <span class="text-amber-600">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Metode</span>
                    <span class="font-medium uppercase">{{ $transaction->metode_pembayaran }}</span>
                </div>
                @if ($transaction->metode_pembayaran === 'cash')
                    <div class="flex justify-between text-gray-500">
                        <span>Uang Diterima</span>
                        <span>Rp {{ number_format($transaction->uang_diterima, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-emerald-600">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="text-center mt-5 pt-4 border-t border-dashed border-gray-200">
                @if ($transaction->catatan)
                    <p class="text-xs text-gray-400 italic mb-2">{{ $transaction->catatan }}</p>
                @endif
                <p class="text-xs text-gray-400">Terima kasih atas kunjungan Anda!</p>
                <p class="text-xs text-gray-300 mt-1">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
            }

            .no-print {
                display: none;
            }
        }
    </style>
@endsection
