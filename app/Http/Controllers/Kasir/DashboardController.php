<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard Kasir.
     */
    public function index(): View
    {
        return view('kasir.dashboard');
    }
}
