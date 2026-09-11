<?php

namespace App\Services;

use App\Models\Aircon;

class AirconService
{
    public function getFiltered(string $search, string $brand, string $status)
    {
        return Aircon::with(['images', 'maintenance'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('item_number', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('area', 'like', "%{$search}%");
                });
            })
            ->when($brand !== '', fn($q) => $q->where('brand', $brand))
            ->when($status !== '', fn($q) => $q->where('status', $status))
            ->orderBy('aircon_id')
            ->paginate(10);
    }

    public function getBrands()
    {
        return Aircon::distinct()->orderBy('brand')->pluck('brand')->filter()->values();
    }

    public function getStatuses()
    {
        return Aircon::distinct()->orderBy('status')->pluck('status')->filter()->values();
    }

    public function stats(): array
    {
        return [
            'total'      => Aircon::count(),
            'working'    => Aircon::where('status', 'Working')->count(),
            'repair'     => Aircon::where('status', 'Under Repair')->count(),
            'value'      => (float) Aircon::sum('purchase_price'),
        ];
    }
}
