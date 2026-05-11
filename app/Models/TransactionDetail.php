<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_id',
        'sparepart_id',
        'qty',
        'harga_beli_saat_transaksi',
        'harga_jual_saat_transaksi',
        'subtotal',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'qty'                       => 'integer',
            'harga_beli_saat_transaksi' => 'decimal:2',
            'harga_jual_saat_transaksi' => 'decimal:2',
            'subtotal'                  => 'decimal:2',
        ];
    }

    // =========================================================================
    // Eloquent Relationships
    // =========================================================================

    /**
     * Transaksi induk dari detail ini.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Sparepart yang tercatat di detail ini.
     * Menggunakan withTrashed() agar detail historis tetap bisa dibaca
     * meskipun sparepart sudah di-soft-delete.
     */
    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(Sparepart::class)->withTrashed();
    }
}
