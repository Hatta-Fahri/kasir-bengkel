<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSparepartRequest;
use App\Http\Requests\Admin\UpdateSparepartRequest;
use App\Models\Sparepart;
use App\Services\SparepartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SparepartController extends Controller
{
    public function __construct(
        private readonly SparepartService $sparepartService
    ) {}

    /**
     * Tampilkan daftar semua sparepart dengan filter & pagination.
     */
    public function index(Request $request): View
    {
        $spareparts   = $this->sparepartService->getPaginated($request->only('search', 'stok_menipis'));
        $totalMenipis = Sparepart::stokMenipis()->count();

        return view('admin.spareparts.index', compact('spareparts', 'totalMenipis'));
    }

    /**
     * Tampilkan form tambah sparepart baru.
     */
    public function create(): View
    {
        return view('admin.spareparts.create');
    }

    /**
     * Simpan sparepart baru ke database.
     */
    public function store(StoreSparepartRequest $request): RedirectResponse
    {
        $this->sparepartService->store($request->validated());

        return redirect()
            ->route('admin.spareparts.index')
            ->with('success', 'Sparepart berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit sparepart.
     */
    public function edit(Sparepart $sparepart): View
    {
        return view('admin.spareparts.edit', compact('sparepart'));
    }

    /**
     * Update data sparepart.
     */
    public function update(UpdateSparepartRequest $request, Sparepart $sparepart): RedirectResponse
    {
        $this->sparepartService->update($sparepart, $request->validated());

        return redirect()
            ->route('admin.spareparts.index')
            ->with('success', "Sparepart \"{$sparepart->nama_part}\" berhasil diperbarui.");
    }

    /**
     * Hapus sparepart (soft delete).
     */
    public function destroy(Sparepart $sparepart): RedirectResponse
    {
        try {
            $this->sparepartService->delete($sparepart);
            return redirect()
                ->route('admin.spareparts.index')
                ->with('success', "Sparepart \"{$sparepart->nama_part}\" berhasil dihapus.");
        } catch (\RuntimeException $e) {
            return redirect()
                ->route('admin.spareparts.index')
                ->with('error', $e->getMessage());
        }
    }
}
