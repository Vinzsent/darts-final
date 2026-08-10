<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Supplier;
use App\Models\SupplyRequest;
use App\Models\Procurement;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getStats(): array
    {
        $user = Auth::user();
        $userType = $user->user_type ?? '';

        $stats = [
            'total_inventory' => Inventory::count(),
            'total_suppliers' => Supplier::count(),
            'pending_requests' => SupplyRequest::where('status', 'Pending')->count(),
            'pending_procurement' => Procurement::where('status', 'Pending')->count(),
            'low_stock_items' => Inventory::whereColumn('current_stock', '<=', 'reorder_level')
                ->where('current_stock', '>', 0)->count(),
            'out_of_stock' => Inventory::where('current_stock', '<=', 0)->count(),
        ];

        return $stats;
    }

    public function getRecentNotifications(int $limit = 5): array
    {
        return [];
    }
}
