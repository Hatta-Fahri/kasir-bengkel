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
        'jasa_items',        // JSON snapshot jasa servis yang dipilih
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
            'jasa_items'         => 'array',   // JSON → array otomatis
        ];
    }

    // =========================================================================
    // Accessors
    // =========================================================================

    /**
     * Accessor: Label status transaksi dalam Bahasa Indonesia.
     */
    protected function labelStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'estimasi'  => 'Estimasi',
                'disetujui' => 'Disetujui',
                'proses'    => 'Proses Servis',
                'selesai'   => 'Selesai',
                'batal'     => 'Dibatalkan',
                default     => ucfirst($this->status),
            },
        );
    }

    /**
     * Accessor: Warna badge status (class Tailwind).
     */
    protected function badgeStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'estimasi'  => 'bg-amber-100 text-amber-800',
                'disetujui' => 'bg-blue-100 text-blue-800',
                'proses'    => 'bg-purple-100 text-purple-800',
                'selesai'   => 'bg-green-100 text-green-800',
                'batal'     => 'bg-red-100 text-red-800',
                default     => 'bg-slate-100 text-slate-800',
            },
        );
    }

    /**
     * Apakah transaksi ini adalah estimasi (belum final)?
     */
    public function isEstimasi(): bool
    {
        return $this->status === 'estimasi';
    }

    /**
     * Apakah transaksi ini masih bisa dibatalkan?
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['estimasi', 'disetujui']);
    }

    /**
     * Apakah transaksi ini sudah final (tidak bisa diubah)?
     */
    public function isFinal(): bool
    {
        return in_array($this->status, ['selesai', 'batal']);
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
