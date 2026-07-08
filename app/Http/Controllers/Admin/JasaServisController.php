<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JasaServis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JasaServisController extends Controller
{
    /**
     * Daftar semua jasa servis.
     */
    public function index(Request $request): View
    {
        $query = JasaServis::orderBy('nama_jasa');

        if ($request->filled('search')) {
            $query->where('nama_jasa', 'like', '%' . $request->search . '%');
        }

        $jasaServisList = $query->paginate(15)->withQueryString();

        return view('admin.jasa-servis.index', compact('jasaServisList'));
    }

    /**
     * Form tambah jasa servis baru.
     */
    public function create(): View
    {
        return view('admin.jasa-servis.create');
    }

    /**
     * Simpan jasa servis baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jasa'      => ['required', 'string', 'max:200'],
            'estimasi_biaya' => ['required', 'numeric', 'min:0'],
            'keterangan'     => ['nullable', 'string', 'max:500'],
            'is_aktif'       => ['boolean'],
        ]);

        JasaServis::create([
            'nama_jasa'      => $validated['nama_jasa'],
            'estimasi_biaya' => $validated['estimasi_biaya'],
            'keterangan'     => $validated['keterangan'] ?? null,
            'is_aktif'       => $request->boolean('is_aktif', true),
        ]);

        return redirect()
            ->route('admin.jasa-servis.index')
            ->with('success', "Jasa \"{$validated['nama_jasa']}\" berhasil ditambahkan.");
    }

    /**
     * Form edit jasa servis.
     */
    public function edit(JasaServis $jasaServis): View
    {
        return view('admin.jasa-servis.edit', compact('jasaServis'));
    }

    /**
     * Update data jasa servis.
     */
    public function update(Request $request, JasaServis $jasaServis): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jasa'      => ['required', 'string', 'max:200'],
            'estimasi_biaya' => ['required', 'numeric', 'min:0'],
            'keterangan'     => ['nullable', 'string', 'max:500'],
            'is_aktif'       => ['boolean'],
        ]);

        $jasaServis->update([
            'nama_jasa'      => $validated['nama_jasa'],
            'estimasi_biaya' => $validated['estimasi_biaya'],
            'keterangan'     => $validated['keterangan'] ?? null,
            'is_aktif'       => $request->boolean('is_aktif', true),
        ]);

        return redirect()
            ->route('admin.jasa-servis.index')
            ->with('success', "Jasa \"{$jasaServis->nama_jasa}\" berhasil diperbarui.");
    }

    /**
     * Hapus jasa servis.
     */
    public function destroy(JasaServis $jasaServis): RedirectResponse
    {
        $nama = $jasaServis->nama_jasa;
        $jasaServis->delete();

        return redirect()
            ->route('admin.jasa-servis.index')
            ->with('success', "Jasa \"{$nama}\" berhasil dihapus.");
    }
}
