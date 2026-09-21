<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\StockLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function getFiltered(?string $search = null, ?string $category = null, ?string $status = null)
    {
        $query = Inventory::with('supplier');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('date_created', 'desc')->paginate(15);
    }

    public function createWithStockLog(array $data): Inventory
    {
        return DB::transaction(function () use ($data) {
            $data['current_stock'] = $data['current_stock'] ?? 0;
            $data['quantity'] = $data['quantity'] ?? 0;
            $data['status'] = $data['status'] ?? 'Active';
            $data['created_by'] = Auth::id();
            $data['date_created'] = now();

            $inventory = Inventory::create($data);

            StockLog::create([
                'inventory_id'    => $inventory->inventory_id,
                'movement_type'   => 'IN',
                'quantity'        => $data['current_stock'],
                'previous_stock'  => 0,
                'new_stock'       => $data['current_stock'],
                'notes'           => $data['received_notes'] ?? 'New item created',
                'created_by'      => Auth::id(),
                'date_created'    => now(),
            ]);

            return $inventory;
        });
    }

    public function updateStock(int $id, int $quantity, string $type, string $notes = ''): Inventory
    {
        return DB::transaction(function () use ($id, $quantity, $type, $notes) {
            $inventory = Inventory::findOrFail($id);
            $previousStock = (int) $inventory->current_stock;

            if ($type === 'add') {
                $newStock = $previousStock + $quantity;
            } elseif ($type === 'subtract') {
                $newStock = max(0, $previousStock - $quantity);
            } else {
                $newStock = $quantity;
            }

            $inventory->current_stock  = $newStock;
            $inventory->last_updated_by = Auth::id();
            $inventory->date_updated   = now();
            $inventory->save();

            StockLog::create([
                'inventory_id'   => $inventory->inventory_id,
                'movement_type'  => $type === 'add' ? 'IN' : ($type === 'subtract' ? 'OUT' : 'ADJUSTMENT'),
                'quantity'       => $quantity,
                'previous_stock' => $previousStock,
                'new_stock'      => $newStock,
                'notes'          => $notes,
                'created_by'     => Auth::id(),
                'date_created'   => now(),
            ]);

            return $inventory->fresh();
        });
    }

    public function getCategories(): array
    {
        return Inventory::select('category')->distinct()->whereNotNull('category')->pluck('category')->toArray();
    }
}
