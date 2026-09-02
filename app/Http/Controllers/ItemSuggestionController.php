<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\PropertyInventory;
use Illuminate\Http\Request;

class ItemSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        if ($q === '') {
            return response()->json(['items' => []]);
        }

        $inventory = Inventory::query()
            ->where('item_name', 'like', "%{$q}%")
            ->orderBy('item_name')
            ->limit(8)
            ->get(['inventory_id', 'item_name', 'category', 'brand', 'color', 'size', 'type', 'unit', 'current_stock', 'status'])
            ->map(fn ($i) => [
                'name' => $i->item_name,
                'category' => $i->category,
                'brand' => $i->brand,
                'color' => $i->color,
                'size' => $i->size,
                'type' => $i->type,
                'unit' => $i->unit,
                'source' => 'Inventory',
                'stock' => (int) ($i->current_stock ?? 0),
                'status' => $i->status,
            ]);

        $property = PropertyInventory::query()
            ->where('item_name', 'like', "%{$q}%")
            ->orderBy('item_name')
            ->limit(8)
            ->get(['item_name', 'category', 'brand', 'color', 'size', 'type', 'unit', 'current_stock', 'status'])
            ->map(fn ($p) => [
                'name' => $p->item_name,
                'category' => $p->category,
                'brand' => $p->brand,
                'color' => $p->color,
                'size' => $p->size,
                'type' => $p->type,
                'unit' => $p->unit,
                'source' => 'Property',
                'stock' => (int) ($p->current_stock ?? 0),
                'status' => $p->status,
            ]);

        return response()->json(['items' => $inventory->concat($property)->values()]);
    }
}
