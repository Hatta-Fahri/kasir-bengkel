<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $stats           = $this->dashboardService->adminStats();
        $terlaris        = $this->dashboardService->sparepartTerlaris(5);
        $stokMenipisList = $this->dashboardService->stokMenipisList(5);

        return view('admin.dashboard', compact('stats', 'terlaris', 'stokMenipisList'));
    }
}
