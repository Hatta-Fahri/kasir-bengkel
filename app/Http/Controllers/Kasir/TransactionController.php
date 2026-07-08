<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kasir\StoreTransactionRequest;
use App\Models\JasaServis;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Riwayat transaksi kasir yang sedang login.
     */
    public function index(Request $request): View
    {
        $query = Transaction::with('kasir')
            ->where('kasir_id', Auth::id())
            ->orderByDesc('created_at');

        // Filter tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter tipe
        if ($request->filled('tipe') && in_array($request->tipe, ['penjualan', 'servis'])) {
            $query->where('tipe_transaksi', $request->tipe);
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Summary filter aktif
        $totalFilteredPendapatan = $query->sum('total_bayar');

        return view('kasir.transactions.index', compact('transactions', 'totalFilteredPendapatan'));
    }

    /**
     * Tampilkan halaman POS kasir.
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
                'harga_jual' => (float) $sp->harga_jual,
                'stok'       => $sp->stok,
            ]);

        // Ambil semua jasa servis yang aktif untuk ditampilkan di POS
        $jasaServis = JasaServis::aktif()
            ->orderBy('nama_jasa')
            ->get()
            ->map(fn ($j) => [
                'id'             => $j->id,
                'nama_jasa'      => $j->nama_jasa,
                'estimasi_biaya' => (float) $j->estimasi_biaya,
                'keterangan'     => $j->keterangan,
            ]);

        return view('kasir.transactions.create', [
            'sparepartsJson' => $spareparts->toJson(),
            'jasaServisJson' => $jasaServis->toJson(),
        ]);
    }

    /**
     * Proses penyimpanan transaksi.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        try {
            $transaction = $this->transactionService->store(
                $request->validated(),
                Auth::id()
            );

            // Jika simpan sebagai estimasi, redirect ke riwayat (belum ke struk)
            if ($transaction->status === 'estimasi') {
                return redirect()
                    ->route('kasir.transactions.index')
                    ->with('success', "Estimasi {$transaction->no_struk} berhasil disimpan. Tunggu persetujuan customer.");
            }

            return redirect()
                ->route('kasir.transactions.receipt', $transaction->id)
                ->with('success', "Transaksi {$transaction->no_struk} berhasil disimpan.");

        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable) {
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem. Transaksi dibatalkan.');
        }
    }

    /**
     * Cetak struk transaksi.
     */
    public function receipt(Transaction $transaction): View
    {
        abort_if($transaction->kasir_id !== Auth::id(), 403);
        $transaction->load(['details.sparepart', 'kasir']);

        return view('kasir.transactions.receipt', compact('transaction'));
    }
}
