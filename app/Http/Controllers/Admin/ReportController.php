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
        $periode = $this->validatedPeriode($request);

        $data = $this->reportService->getLaporan(
            $periode,
            $request->bulan,
            $request->minggu,
            $request->tanggal,
            $request->tahun,
        );

        return view('admin.reports.index', array_merge($data, ['periode' => $periode]));
    }

    /**
     * Tampilkan laporan keuangan siap cetak sesuai filter periode yang aktif.
     */
    public function print(Request $request): View
    {
        $periode = $this->validatedPeriode($request);

        $data = $this->reportService->getLaporan(
            $periode,
            $request->bulan,
            $request->minggu,
            $request->tanggal,
            $request->tahun,
            export: true,
        );

        return view('admin.reports.print', array_merge($data, [
            'periode'    => $periode,
            'printedBy'  => $request->user()->name,
            'printedAt'  => now(),
        ]));
    }

    private function validatedPeriode(Request $request): string
    {
        $request->validate([
            'periode' => ['nullable', 'in:harian,mingguan,bulanan,tahunan'],
            'bulan'   => ['nullable', 'date_format:Y-m'],
            'minggu'  => ['nullable', 'date'],
            'tanggal' => ['nullable', 'date'],
            'tahun'   => ['nullable', 'date_format:Y'],
        ]);

        return $request->input('periode', 'bulanan');
    }
}
