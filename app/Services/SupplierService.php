<?php

namespace App\Services;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;

class SupplierService
{
    public function getFiltered(?string $search = null): Builder
    {
        $query = Supplier::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%")
                  ->orWhere('business_type', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('supplier_name');
    }

    public function getSupplierStats(int $id): array
    {
        $supplier = Supplier::findOrFail($id);

        return [
            'total_procurements' => $supplier->procurements()->count(),
            'total_transactions' => $supplier->transactions()->count(),
            'total_inventory_items' => $supplier->inventory()->count(),
            'pending_procurements' => $supplier->procurements()->where('status', 'Pending')->count(),
            'recent_procurements' => $supplier->procurements()
                ->orderBy('date_received', 'desc')
                ->take(5)
                ->get(),
        ];
    }
}
