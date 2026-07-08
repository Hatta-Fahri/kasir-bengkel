<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstimasiController extends Controller
{
    /**
     * Setujui estimasi → status: disetujui, lanjut ke proses servis.
     */
    public function approve(Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->kasir_id !== Auth::id(), 403);
        abort_if(!$transaction->isEstimasi(), 422, 'Hanya estimasi yang bisa disetujui.');

        // Potong stok sparepart sekarang (karena saat estimasi dibuat, stok belum dipotong)
        $transaction->load('details.sparepart');
        foreach ($transaction->details as $detail) {
            if ($detail->sparepart && $detail->sparepart->stok < $detail->qty) {
                return back()->with('error', "Stok \"{$detail->sparepart->nama_part}\" tidak cukup (tersedia: {$detail->sparepart->stok}). Estimasi tidak bisa disetujui.");
            }
        }
        foreach ($transaction->details as $detail) {
            $detail->sparepart?->decrement('stok', $detail->qty);
        }

        $transaction->update(['status' => 'proses']);

        return redirect()
            ->route('kasir.transactions.index')
            ->with('success', "Estimasi {$transaction->no_struk} disetujui, kendaraan masuk proses servis.");
    }

    /**
     * Tolak/batalkan estimasi → status: batal. Stok sparepart dikembalikan.
     */
    public function cancel(Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->kasir_id !== Auth::id(), 403);
        abort_if(!$transaction->canBeCancelled(), 422, 'Transaksi ini tidak bisa dibatalkan.');

        // Kembalikan stok sparepart jika ada
        foreach ($transaction->details as $detail) {
            $detail->sparepart?->increment('stok', $detail->qty);
        }

        $transaction->update(['status' => 'batal']);

        return redirect()
            ->route('kasir.transactions.index')
            ->with('success', "Estimasi {$transaction->no_struk} dibatalkan. Stok sparepart dikembalikan.");
    }

    /**
     * Selesaikan servis → status: selesai (siap bayar).
     */
    public function complete(Request $request, Transaction $transaction): RedirectResponse
    {
        abort_if($transaction->kasir_id !== Auth::id(), 403);
        abort_if($transaction->status !== 'proses', 422, 'Hanya servis yang sedang berjalan yang bisa diselesaikan.');

        $request->validate([
            'metode_pembayaran' => 'required|in:cash,qris,xendit',
            'uang_diterima'     => 'nullable|numeric|min:0',
        ]);

        $uangDiterima = null;
        $kembalian    = null;

        if ($request->metode_pembayaran === 'cash') {
            $uangDiterima = (float) $request->uang_diterima;
            if ($uangDiterima < $transaction->total_bayar) {
                return back()->with('error', 'Uang diterima kurang dari total tagihan.');
            }
            $kembalian = $uangDiterima - $transaction->total_bayar;
        } elseif ($request->metode_pembayaran === 'xendit') {
            // Hit Xendit API
            $secretKey = config('services.xendit.secret_key');
            if (empty($secretKey) || $secretKey === 'isi_secret_key_disini') {
                return back()->with('error', 'Xendit Secret Key belum dikonfigurasi. Hubungi Admin.');
            }

            try {
                $response = \Illuminate\Support\Facades\Http::withBasicAuth($secretKey, '')
                    ->post('https://api.xendit.co/v2/invoices', [
                        'external_id' => $transaction->no_struk . '_' . time(),
                        'amount'      => (float) $transaction->total_bayar,
                        'description' => 'Pembayaran Servis ' . $transaction->no_struk,
                        // 'payer_email' => ... optional
                        'success_redirect_url' => route('kasir.transactions.index'),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Update transaksi, tapi statusnya masih 'proses' atau biarkan saja
                    // Karena invoice belum dibayar
                    $transaction->update([
                        'metode_pembayaran' => 'xendit',
                        'xendit_invoice_id' => $data['id'] ?? null,
                        'payment_url'       => $data['invoice_url'] ?? null,
                    ]);

                    return redirect()->route('kasir.transactions.index')
                        ->with('success', 'Invoice Xendit berhasil dibuat. Silakan arahkan pelanggan untuk membayar melalui tautan yang disediakan.');
                }

                return back()->with('error', 'Gagal membuat Invoice Xendit: ' . $response->body());
            } catch (\Exception $e) {
                return back()->with('error', 'Terjadi kesalahan sistem saat menghubungi Xendit: ' . $e->getMessage());
            }
        }

        $transaction->update([
            'status'            => 'selesai',
            'metode_pembayaran' => $request->metode_pembayaran,
            'uang_diterima'     => $uangDiterima,
            'kembalian'         => $kembalian,
        ]);

        return redirect()
            ->route('kasir.transactions.receipt', $transaction->id)
            ->with('success', "Servis {$transaction->no_struk} selesai! Silakan cetak struk.");
    }
}
