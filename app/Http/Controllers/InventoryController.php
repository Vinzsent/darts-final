<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Inventory;
use App\Models\StockLog;
use App\Models\Supplier;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    private function generateQrcode(array $data): string
    {
        $payload = [
            'type'     => 'INVENTORY_ITEM',
            'name'     => $data['item_name'] ?? '',
            'category' => $data['category'] ?? '',
            'brand'    => $data['brand'] ?? '',
            'sku'      => 'INV-' . now()->format('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)),
        ];

        return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');

        $items = $this->inventoryService->getFiltered($search, $category, $status);
        $categories = $this->inventoryService->getCategories();

        return view('inventory.index', compact('items', 'categories', 'search', 'category', 'status'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'Active')->orderBy('supplier_name')->get();
        return view('inventory.create', compact('suppliers'));
    }

    public function store(StoreInventoryRequest $request)
    {
        $data = $request->validated();

        // Automatic mode (or manual left empty): generate a QR code server-side
        if ($request->input('qrcode_mode') !== 'manual' || empty($data['qrcode'])) {
            $data['qrcode'] = $this->generateQrcode($data);
        }

        $item = $this->inventoryService->createWithStockLog($data);

        return redirect()->route('inventory.index')
            ->with('success', "Inventory item '{$item->item_name}' created successfully.");
    }

    public function show(int $id)
    {
        $item = Inventory::with(['supplier', 'creator', 'updater', 'stockLogs' => function ($q) {
            $q->orderBy('date_created', 'desc')->limit(50);
        }])->findOrFail($id);

        return view('inventory.show', compact('item'));
    }

    public function edit(int $id)
    {
        $item = Inventory::findOrFail($id);
        $suppliers = Supplier::where('status', 'Active')->orderBy('supplier_name')->get();
        return view('inventory.edit', compact('item', 'suppliers'));
    }

    public function update(UpdateInventoryRequest $request, int $id)
    {
        $item = Inventory::findOrFail($id);
        $data = $request->validated();
        $data['unit_cost'] = $data['unit_cost'] ?? 0;
        $data['current_stock'] = $data['current_stock'] ?? 0;
        $data['last_updated_by'] = auth()->id();
        $data['date_updated'] = now();
        $item->update($data);

        return redirect()->route('inventory.index')
            ->with('success', "Inventory item '{$item->item_name}' updated successfully.");
    }

    public function destroy(int $id)
    {
        $item = Inventory::findOrFail($id);
        $name = $item->item_name;
        $item->delete();

        return redirect()->route('inventory.index')
            ->with('success', "Inventory item '{$name}' deleted successfully.");
    }

    public function movements(int $id)
    {
        $item = Inventory::with('supplier')->findOrFail($id);
        $logs = StockLog::where('inventory_id', $id)
            ->orderBy('date_created', 'desc')
            ->paginate(20);

        return view('inventory.show', compact('item', 'logs'));
    }

    public function stockAdjust(Request $request, int $id)
    {
        $request->validate([
            'type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $item = Inventory::findOrFail($id);
        $this->inventoryService->updateStock(
            $id,
            $request->quantity,
            $request->type,
            $request->notes ?? ''
        );

        return redirect()->route('inventory.show', $id)
            ->with('success', "Stock adjusted for '{$item->item_name}'.");
    }
}
