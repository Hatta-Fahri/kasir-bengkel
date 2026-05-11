<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'admin_id',
        'nama_pengeluaran',
        'kategori',
        'jumlah',
        'tanggal_pengeluaran',
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
            'jumlah'              => 'decimal:2',
            'tanggal_pengeluaran' => 'date',
        ];
    }

    // =========================================================================
    // Eloquent Relationships
    // =========================================================================

    /**
     * Admin yang mencatat pengeluaran ini.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
