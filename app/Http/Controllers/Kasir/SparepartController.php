<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Sparepart;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SparepartController extends Controller
{
    /**
     * Tampilkan daftar stok sparepart (read-only untuk Kasir).
     */
    public function index(Request $request): View
    {
        $spareparts = Sparepart::query()
            ->when($request->search, fn ($q, $s) =>
                $q->where('nama_part', 'like', "%{$s}%")
                  ->orWhere('kode_part', 'like', "%{$s}%")
                  ->orWhere('kategori', 'like', "%{$s}%")
            )
            ->when($request->stok_menipis, fn ($q) => $q->stokMenipis())
            ->orderBy('nama_part')
            ->paginate(20)
            ->withQueryString();

        $totalMenipis = Sparepart::stokMenipis()->count();

        return view('kasir.spareparts.index', compact('spareparts', 'totalMenipis'));
    }
}
