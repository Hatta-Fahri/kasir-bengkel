<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportService $reportService
    ) {}

    /**
     * Tampilkan laporan keuangan dengan filter periode.
     */
    public function index(Request $request): View
    {
        $request->validate([
            'periode' => ['nullable', 'in:harian,mingguan,bulanan'],
            'bulan'   => ['nullable', 'date_format:Y-m'],
            'minggu'  => ['nullable', 'date'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $periode = $request->input('periode', 'bulanan');

        $data = $this->reportService->getLaporan(
            $periode,
            $request->bulan,
            $request->minggu,
            $request->tanggal,
        );

        return view('admin.reports.index', array_merge($data, ['periode' => $periode]));
    }
}
