<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpenseService
{
    /**
     * Ambil daftar pengeluaran dengan pagination, diurutkan terbaru.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Expense::with('admin')
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('nama_pengeluaran', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->when($filters['bulan'] ?? null, function ($q, $bulan) {
                // format: YYYY-MM
                $q->whereYear('tanggal_pengeluaran', substr($bulan, 0, 4))
                  ->whereMonth('tanggal_pengeluaran', substr($bulan, 5, 2));
            })
            ->orderByDesc('tanggal_pengeluaran')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Simpan pengeluaran baru. admin_id diisi dari parameter, bukan dari form.
     */
    public function store(array $data, int $adminId): Expense
    {
        return Expense::create(array_merge($data, ['admin_id' => $adminId]));
    }

    /**
     * Update data pengeluaran.
     */
    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense->fresh();
    }

    /**
     * Hapus pengeluaran secara permanen (expenses tidak pakai SoftDelete).
     */
    public function delete(Expense $expense): void
    {
        $expense->delete();
    }

    /**
     * Total pengeluaran bulan ini untuk ditampilkan di header.
     */
    public function totalBulanIni(): float
    {
        return Expense::whereYear('tanggal_pengeluaran', now()->year)
            ->whereMonth('tanggal_pengeluaran', now()->month)
            ->sum('jumlah');
    }
}
