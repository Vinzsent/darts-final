<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\PropertyInventory;
use App\Models\PropertyStockLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $category = $request->get('category');
        $status = $request->get('status');

        $query = PropertyInventory::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        if ($category) {
            $query->where('category', $category);
        }
        if ($status) {
            $query->where('status', $status);
        }

        $properties = $query->orderBy('item_name')->paginate(10)->withQueryString();
        $categories = PropertyInventory::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('property.index', compact('properties', 'categories', 'search', 'category', 'status'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        return view('property.create', compact('suppliers'));
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();

        // Barcode: auto-generate or validate manual entry
        if (($request->input('barcode_mode') ?? 'auto') === 'manual' && !empty($data['barcode'])) {
            abort_if(PropertyInventory::where('barcode', $data['barcode'])->exists(), 422, 'Barcode already exists.');
        } else {
            $data['barcode'] = $this->generateBarcode();
        }

        $data['current_stock'] = $data['current_stock'] ?? 0;
        $data['quantity'] = $data['quantity'] ?? 0;
        $data['reorder_level'] = $data['reorder_level'] ?? 0;
        $data['status'] = $data['status'] ?? 'Active';
        $data['created_by'] = auth()->id();
        $data['date_created'] = now();

        $property = PropertyInventory::create($data);

        $stock = (int) $data['current_stock'];
        if ($stock > 0) {
            PropertyStockLog::create([
                'inventory_id' => $property->inventory_id,
                'movement_type' => 'IN',
                'quantity' => $stock,
                'previous_stock' => 0,
                'new_stock' => $stock,
                'notes' => 'Initial stock',
                'receiver' => $data['receiver'] ?? null,
                'created_by' => auth()->id(),
                'date_created' => now(),
            ]);
        }

        return redirect()->route('property.index')
            ->with('success', "Property item '{$property->item_name}' created successfully.");
    }

    public function show(int $id)
    {
        $property = PropertyInventory::with(['supplier', 'creator', 'updater', 'stockLogs.creator'])
            ->findOrFail($id);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)
            ->errorCorrection('H')
            ->generate($property->qrcode ?? $property->barcode ?? $property->item_name);

        return view('property.show', compact('property', 'qrCode'));
    }

    public function edit(int $id)
    {
        $property = PropertyInventory::findOrFail($id);
        $suppliers = Supplier::orderBy('supplier_name')->get();
        return view('property.edit', compact('property', 'suppliers'));
    }

    public function update(UpdatePropertyRequest $request, int $id)
    {
        $property = PropertyInventory::findOrFail($id);
        $data = $request->validated();
        $data['last_updated_by'] = auth()->id();
        $data['date_updated'] = now();
        $property->update($data);

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
        $validated = $request->validate([
            'type' => ['required', Rule::in(['add', 'subtract', 'set'])],
            'quantity' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $property = PropertyInventory::findOrFail($id);
        $previous = (int) $property->current_stock;
        $qty = (int) $validated['quantity'];

        $new = match ($validated['type']) {
            'add' => $previous + $qty,
            'subtract' => $previous - $qty,
            'set' => $qty,
        };

        if ($new < 0) {
            return back()->withErrors(['quantity' => 'Stock cannot go below zero.']);
        }

        $property->update([
            'current_stock' => $new,
            'last_updated_by' => auth()->id(),
            'date_updated' => now(),
        ]);

        PropertyStockLog::create([
            'inventory_id' => $property->inventory_id,
            'movement_type' => match ($validated['type']) {
                'add' => 'IN',
                'subtract' => 'OUT',
                'set' => 'ADJUST',
            },
            'quantity' => $validated['type'] === 'set' ? abs($new - $previous) : $qty,
            'previous_stock' => $previous,
            'new_stock' => $new,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
            'date_created' => now(),
        ]);

        return redirect()->route('property.show', $property->inventory_id)
            ->with('success', 'Stock adjusted successfully.');
    }

    private function generateBarcode(): string
    {
        do {
            $barcode = 'PRP-' . now()->format('Ymd') . '-' . str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        } while (PropertyInventory::where('barcode', $barcode)->exists());

        return $barcode;
    }
}
