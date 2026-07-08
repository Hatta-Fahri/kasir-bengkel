<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JasaServis extends Model
{
    use HasFactory;

    protected $table = 'jasa_servis';

    protected $fillable = [
        'nama_jasa',
        'estimasi_biaya',
        'keterangan',
        'is_aktif',
    ];

    protected function casts(): array
    {
        return [
            'estimasi_biaya' => 'decimal:2',
            'is_aktif'       => 'boolean',
        ];
    }

    /**
     * Scope: Hanya jasa yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
