<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    public function index()
    {
        $stats = $this->dashboardService->getStats();
        $notifications = $this->dashboardService->getRecentNotifications();

        return view('dashboard.index', compact('stats', 'notifications'));
    }
}
