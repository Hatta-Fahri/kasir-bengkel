<?php

namespace App\Services;

use App\Models\Sparepart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Proses dan simpan transaksi baru secara atomik (DB Transaction).
     *
     * @throws \Throwable
     */
    public function store(array $data, int $kasirId): Transaction
    {
        return DB::transaction(function () use ($data, $kasirId) {

            $isEstimasi = !empty($data['is_estimasi']); // Mode estimasi: tidak potong stok dulu

            // 1. Hitung subtotal sparepart dari keranjang
            $subtotalSparepart = 0.0;
            $itemsToSave       = [];

            if (!empty($data['items'])) {
                foreach ($data['items'] as $item) {
                    /** @var Sparepart $sparepart */
                    $sparepart = Sparepart::lockForUpdate()->findOrFail($item['sparepart_id']);

                    // Validasi stok sekali lagi di dalam transaksi DB (race condition guard)
                    if ($sparepart->stok < $item['qty']) {
                        throw new \RuntimeException(
                            "Stok \"{$sparepart->nama_part}\" tidak cukup. Tersedia: {$sparepart->stok}."
                        );
                    }

                    $hargaBeli = (float) $sparepart->harga_beli;
                    $hargaJual = (float) $sparepart->harga_jual; // accessor HPP +10%
                    $subtotal  = $hargaJual * $item['qty'];
                    $subtotalSparepart += $subtotal;

                    $itemsToSave[] = [
                        'sparepart'  => $sparepart,
                        'qty'        => $item['qty'],
                        'harga_beli' => $hargaBeli,
                        'harga_jual' => $hargaJual,
                        'subtotal'   => $subtotal,
                    ];
                }
            }

            // 2. Hitung total ongkos jasa dari jasa_items yang dipilih
            $jasaItemsData = [];
            $ongkosJasa    = 0.0;

            if (!empty($data['jasa_items'])) {
                foreach ($data['jasa_items'] as $jasa) {
                    $jasaItemsData[] = [
                        'jasa_servis_id' => $jasa['id'],
                        'nama_jasa'      => $jasa['nama_jasa'],
                        'estimasi_biaya' => (float) $jasa['estimasi_biaya'],
                    ];
                    $ongkosJasa += (float) $jasa['estimasi_biaya'];
                }
            } else {
                // Fallback: input manual ongkos jasa (untuk backward compatibility)
                $ongkosJasa = (float) ($data['ongkos_jasa'] ?? 0);
            }

            $totalBayar = $subtotalSparepart + $ongkosJasa;

            // 2. Hitung kembalian (hanya untuk cash)
            $uangDiterima = null;
            $kembalian    = null;
            if ($data['metode_pembayaran'] === 'cash') {
                $uangDiterima = (float) $data['uang_diterima'];
                $kembalian    = $uangDiterima - $totalBayar;
            }

            // 3. Simpan header transaksi
            $transaction = Transaction::create([
                'no_struk'           => $this->generateNoStruk(),
                'kasir_id'           => $kasirId,
                'tipe_transaksi'     => $data['tipe_transaksi'],
                'plat_nomor'         => $data['plat_nomor'] ?? null,
                'jenis_mobil'        => $data['jenis_mobil'] ?? null,
                'ongkos_jasa'        => $ongkosJasa,
                'jasa_items'         => !empty($jasaItemsData) ? $jasaItemsData : null,
                'subtotal_sparepart' => $subtotalSparepart,
                'total_bayar'        => $totalBayar,
                'metode_pembayaran'  => $data['metode_pembayaran'],
                'uang_diterima'      => $uangDiterima,
                'kembalian'          => $kembalian,
                'status'             => $isEstimasi ? 'estimasi' : 'selesai',
                'catatan'            => $data['catatan'] ?? null,
            ]);

            // 4. Simpan detail & potong stok
            //    Jika estimasi: simpan detail tetapi TIDAK potong stok dulu.
            //    Stok akan dipotong saat estimasi disetujui (approve).
            foreach ($itemsToSave as $item) {
                TransactionDetail::create([
                    'transaction_id'            => $transaction->id,
                    'sparepart_id'              => $item['sparepart']->id,
                    'qty'                       => $item['qty'],
                    'harga_beli_saat_transaksi' => $item['harga_beli'],
                    'harga_jual_saat_transaksi' => $item['harga_jual'],
                    'subtotal'                  => $item['subtotal'],
                ]);

                // Hanya potong stok jika BUKAN estimasi
                if (!$isEstimasi) {
                    $item['sparepart']->decrement('stok', $item['qty']);
                }
            }

            return $transaction;
        });
    }

    /**
     * Generate nomor struk unik: TRX-YYYYMMDD-XXX (urut per hari).
     */
    private function generateNoStruk(): string
    {
        $tanggal = now()->format('Ymd');
        $prefix  = "TRX-{$tanggal}-";

        // Hitung berapa transaksi sudah ada hari ini
        $count = Transaction::where('no_struk', 'like', "{$prefix}%")->count();
        $urut  = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        // Guard: jika no_struk sudah ada (race condition), coba urutan berikutnya
        while (Transaction::where('no_struk', $prefix . $urut)->exists()) {
            $urut = str_pad((int)$urut + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . $urut;
    }
}
