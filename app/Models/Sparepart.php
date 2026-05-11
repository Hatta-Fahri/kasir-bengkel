<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sparepart extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'kode_part',
        'nama_part',
        'merek',
        'kategori',
        'stok',
        'stok_minimum',
        'harga_beli',
        'satuan',
        'keterangan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga_beli'    => 'decimal:2',
            'stok'          => 'integer',
            'stok_minimum'  => 'integer',
        ];
    }

    // =========================================================================
    // Accessors / Computed Attributes
    // =========================================================================

    /**
     * Accessor: Harga Jual = Harga Beli + 10% (markup otomatis).
     * Nilai ini TIDAK disimpan di database, dihitung secara dinamis.
     *
     * Penggunaan: $sparepart->harga_jual
     */
    protected function hargaJual(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->harga_beli * 1.10, 2),
        );
    }

    /**
     * Accessor: Cek apakah stok sudah mencapai atau di bawah batas minimum.
     *
     * Penggunaan: $sparepart->is_stok_menipis  → returns bool
     */
    protected function isStokMenipis(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stok <= $this->stok_minimum,
        );
    }

    // =========================================================================
    // Query Scopes
    // =========================================================================

    /**
     * Scope: Filter sparepart dengan stok menipis (stok <= stok_minimum).
     *
     * Penggunaan: Sparepart::stokMenipis()->get()
     */
    #[Scope]
    protected function stokMenipis(Builder $query): void
    {
        $query->whereColumn('stok', '<=', 'stok_minimum');
    }

    // =========================================================================
    // Eloquent Relationships
    // =========================================================================

    /**
     * Detail transaksi yang menyertakan sparepart ini.
     */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    /**
     * Prediksi kebutuhan sparepart ini dari model Prophet.
     */
    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}
