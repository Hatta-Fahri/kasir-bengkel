<?php

namespace App\Services;

use App\Models\Sparepart;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class SparepartService
{
    /**
     * Ambil daftar sparepart dengan pagination & filter pencarian.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Sparepart::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('nama_part', 'like', "%{$search}%")
                      ->orWhere('kode_part', 'like', "%{$search}%")
                      ->orWhere('merek', 'like', "%{$search}%")
                      ->orWhere('kategori', 'like', "%{$search}%");
                });
            })
            ->when($filters['stok_menipis'] ?? false, fn ($q) => $q->stokMenipis())
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Buat sparepart baru.
     * Kode part di-generate otomatis jika tidak diisi.
     */
    public function store(array $data): Sparepart
    {
        if (empty($data['kode_part'])) {
            $data['kode_part'] = $this->generateKodePart();
        }

        return Sparepart::create($data);
    }

    /**
     * Update data sparepart.
     */
    public function update(Sparepart $sparepart, array $data): Sparepart
    {
        $sparepart->update($data);
        return $sparepart->fresh();
    }

    /**
     * Hapus sparepart (soft delete).
     * Cegah penghapusan jika sparepart pernah digunakan di transaksi.
     *
     * @throws \RuntimeException
     */
    public function delete(Sparepart $sparepart): void
    {
        if ($sparepart->transactionDetails()->exists()) {
            throw new \RuntimeException(
                "Sparepart \"{$sparepart->nama_part}\" tidak dapat dihapus karena sudah digunakan dalam transaksi."
            );
        }

        $sparepart->delete();
    }

    /**
     * Generate kode part otomatis: SP-XXXX (4 digit angka acak unik).
     */
    private function generateKodePart(): string
    {
        do {
            $kode = 'SP-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (Sparepart::withTrashed()->where('kode_part', $kode)->exists());

        return $kode;
    }
}
