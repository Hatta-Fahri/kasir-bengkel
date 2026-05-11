<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'no_struk',
        'kasir_id',
        'tipe_transaksi',
        'plat_nomor',
        'jenis_mobil',
        'ongkos_jasa',
        'subtotal_sparepart',
        'total_bayar',
        'metode_pembayaran',
        'uang_diterima',
        'kembalian',
        'status',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'ongkos_jasa'        => 'decimal:2',
            'subtotal_sparepart' => 'decimal:2',
            'total_bayar'        => 'decimal:2',
            'uang_diterima'      => 'decimal:2',
            'kembalian'          => 'decimal:2',
        ];
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    /**
     * Accessor: Apakah transaksi ini bertipe servis?
     *
     * Penggunaan: $transaction->is_servis  → bool
     */
    protected function isServis(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tipe_transaksi === 'servis',
        );
    }

    /**
     * Accessor: Label metode pembayaran yang lebih mudah dibaca.
     *
     * Penggunaan: $transaction->label_pembayaran  → 'Tunai (Cash)' atau 'QRIS'
     */
    protected function labelPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->metode_pembayaran) {
                'cash'  => 'Tunai (Cash)',
                'qris'  => 'QRIS',
                default => ucfirst($this->metode_pembayaran),
            },
        );
    }

    // =========================================================================
    // Eloquent Relationships
    // =========================================================================

    /**
     * Kasir yang melakukan transaksi ini.
     */
    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    /**
     * Detail item sparepart dalam transaksi ini.
     */
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
