<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExpenseRequest;
use App\Http\Requests\Admin\UpdateExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function __construct(
        private readonly ExpenseService $expenseService
    ) {}

    /**
     * Tampilkan daftar pengeluaran dengan filter & pagination.
     */
    public function index(Request $request): View
    {
        $expenses     = $this->expenseService->getPaginated($request->only('search', 'bulan'));
        $totalBulanIni = $this->expenseService->totalBulanIni();

        return view('admin.expenses.index', compact('expenses', 'totalBulanIni'));
    }

    /**
     * Tampilkan form tambah pengeluaran.
     */
    public function create(): View
    {
        return view('admin.expenses.create');
    }

    /**
     * Simpan pengeluaran baru. admin_id diambil dari Auth, bukan dari form.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $this->expenseService->store($request->validated(), Auth::id());

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Tampilkan form edit pengeluaran.
     */
    public function edit(Expense $expense): View
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    /**
     * Update data pengeluaran.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->expenseService->update($expense, $request->validated());

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Hapus pengeluaran.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $this->expenseService->delete($expense);

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
