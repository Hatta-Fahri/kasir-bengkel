<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\StoreTransactionRequest;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Tampilkan halaman POS kasir.
     * Sparepart yang stok > 0 dikirim sebagai JSON untuk diolah Alpine.js.
     */
    public function create(): View
    {
        $spareparts = Sparepart::select('id', 'nama_part', 'kode_part', 'satuan', 'harga_beli', 'stok')
            ->where('stok', '>', 0)
            ->orderBy('nama_part')
            ->get()
            ->map(fn ($sp) => [
                'id'         => $sp->id,
                'nama'       => $sp->nama_part,
                'kode'       => $sp->kode_part,
                'satuan'     => $sp->satuan,
                'harga_jual' => (float) $sp->harga_jual, // accessor HPP +10%
                'stok'       => $sp->stok,
            ]);

        return view('kasir.transactions.create', [
            'sparepartsJson' => $spareparts->toJson(),
        ]);
    }

    /**
     * Proses penyimpanan transaksi via TransactionService.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        try {
            $transaction = $this->transactionService->store(
                $request->validated(),
                Auth::id()
            );

            return redirect()
                ->route('kasir.transactions.receipt', $transaction->id)
                ->with('success', "Transaksi {$transaction->no_struk} berhasil disimpan.");

        } catch (\RuntimeException $e) {
            // Stok tidak cukup (race condition yang terdeteksi di service)
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Transaksi dibatalkan.');
        }
    }

    /**
     * Tampilkan halaman cetak struk.
     */
    public function receipt(Transaction $transaction): View
    {
        // Guard: kasir hanya bisa lihat struk transaksi miliknya sendiri
        abort_if($transaction->kasir_id !== Auth::id(), 403);

        $transaction->load(['details.sparepart', 'kasir']);

        return view('kasir.transactions.receipt', compact('transaction'));
    }
}
