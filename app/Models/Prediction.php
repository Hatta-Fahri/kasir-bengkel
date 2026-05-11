<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'sparepart_id',
        'bulan_prediksi',
        'estimasi_kebutuhan',
        'batas_bawah',
        'batas_atas',
        'versi_model',
        'di_generate_pada',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'bulan_prediksi'     => 'date',
            'estimasi_kebutuhan' => 'decimal:2',
            'batas_bawah'        => 'decimal:2',
            'batas_atas'         => 'decimal:2',
            'di_generate_pada'   => 'datetime',
        ];
    }

    // =========================================================================
    // Eloquent Relationships
    // =========================================================================

    /**
     * Sparepart yang diprediksi.
     */
    public function sparepart(): BelongsTo
    {
        return $this->belongsTo(Sparepart::class)->withTrashed();
    }
}
