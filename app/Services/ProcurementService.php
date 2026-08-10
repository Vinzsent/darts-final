<?php

namespace App\Services;

use App\Models\Procurement;

class ProcurementService
{
    public function getFiltered(?string $search = null, ?string $status = null)
    {
        $query = Procurement::with('supplier');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('item_description', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('supplier_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('date_received', 'desc')->paginate(15);
    }

    public function getStatusHistory(int $id): Procurement
    {
        return Procurement::with('supplier')->findOrFail($id);
    }
}
