<?php

namespace App\Http\Controllers;

use App\Models\PropertyInventory;
use App\Models\PropertyStockLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    private function generateBarcode(): string
    {
        do {
            $barcode = 'PRP-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (PropertyInventory::where('barcode', $barcode)->exists());

        return $barcode;
    }
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');

        $query = PropertyInventory::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $properties = $query->orderBy('date_created', 'desc')->paginate(15);
        $categories = PropertyInventory::select('category')->distinct()->whereNotNull('category')->pluck('category')->toArray();

        return view('property.index', compact('properties', 'categories', 'search', 'category', 'status'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'Active')->orderBy('supplier_name')->get();
        return view('property.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'current_stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_cost' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'receiver' => 'nullable|string|max:255',
            'status' => 'nullable|in:Active,Inactive,Discontinued',
            'received_notes' => 'nullable|string|max:255',
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('property_inventory', 'barcode')],
        ]);

        // Automatic mode (or manual left empty): generate a unique barcode server-side
        if ($request->input('barcode_mode') !== 'manual' || empty($validated['barcode'])) {
            $validated['barcode'] = $this->generateBarcode();
        }

        $validated['current_stock'] = $validated['current_stock'] ?? 0;
        $validated['quantity'] = $validated['quantity'] ?? 0;
        $validated['status'] = $validated['status'] ?? 'Active';
        $validated['created_by'] = auth()->id();
        $validated['date_created'] = now();

        $property = PropertyInventory::create($validated);

        if ($validated['current_stock'] > 0) {
            PropertyStockLog::create([
                'inventory_id'    => $property->inventory_id,
                'movement_type'   => 'IN',
                'quantity'        => $validated['current_stock'],
                'previous_stock'  => 0,
                'new_stock'       => $validated['current_stock'],
                'notes'           => $validated['received_notes'] ?? 'New item created',
                'created_by'      => auth()->id(),
                'date_created'    => now(),
            ]);
        }

        return redirect()->route('property.index')
            ->with('success', "Property item '{$property->item_name}' created successfully.");
    }

    public function show(int $id)
    {
        $property = PropertyInventory::with('stockLogs')->findOrFail($id);
        return view('property.show', compact('property'));
    }

    public function edit(int $id)
    {
        $property = PropertyInventory::findOrFail($id);
        $suppliers = Supplier::where('status', 'Active')->orderBy('supplier_name')->get();
        return view('property.edit', compact('property', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        $property = PropertyInventory::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'current_stock' => 'nullable|integer|min:0',
            'quantity' => 'nullable|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'unit_cost' => 'nullable|numeric|min:0',
            'reorder_level' => 'nullable|integer|min:0',
            'supplier_id' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'receiver' => 'nullable|string|max:255',
            'status' => 'nullable|in:Active,Inactive,Discontinued',
            'received_notes' => 'nullable|string|max:255',
            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('property_inventory', 'barcode')->ignore($property->inventory_id)],
        ]);

        // Keep existing barcode unless a manual one is provided
        if ($request->input('barcode_mode') === 'manual' && !empty($validated['barcode'])) {
            $validated['barcode'] = trim($validated['barcode']);
        } else {
            $validated['barcode'] = $property->barcode ?? $this->generateBarcode();
        }

        $validated['last_updated_by'] = auth()->id();
        $validated['date_updated'] = now();
        $property->update($validated);

        return redirect()->route('property.index')
            ->with('success', "Property item '{$property->item_name}' updated successfully.");
    }

    public function destroy(int $id)
    {
        $property = PropertyInventory::findOrFail($id);
        $name = $property->item_name;
        $property->delete();

        return redirect()->route('property.index')
            ->with('success', "Property item '{$name}' deleted successfully.");
    }

    public function stockAdjust(Request $request, int $id)
    {
        $request->validate([
            'type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $property = PropertyInventory::findOrFail($id);

        return DB::transaction(function () use ($property, $request) {
            $oldStock = $property->current_stock;

            if ($request->type === 'add') {
                $property->increment('current_stock', $request->quantity);
            } elseif ($request->type === 'subtract') {
                $newStock = max(0, $property->current_stock - $request->quantity);
                $property->current_stock = $newStock;
                $property->save();
            } else {
                $property->current_stock = $request->quantity;
                $property->save();
            }

            $property->last_updated_by = auth()->id();
            $property->date_updated = now();
            $property->save();

            $newStock = $property->current_stock;

            PropertyStockLog::create([
                'inventory_id'    => $property->inventory_id,
                'movement_type'   => $request->type === 'add' ? 'IN' : ($request->type === 'subtract' ? 'OUT' : 'ADJUSTMENT'),
                'quantity'        => $request->quantity,
                'previous_stock'  => max(0, $oldStock),
                'new_stock'       => $newStock,
                'notes'           => $request->notes ?? '',
                'created_by'      => auth()->id(),
                'date_created'    => now(),
            ]);

            return redirect()->route('property.show', $property->inventory_id)
                ->with('success', "Stock adjusted for '{$property->item_name}'.");
        });
    }
}
