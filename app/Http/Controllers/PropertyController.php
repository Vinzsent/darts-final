<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\PropertyStockLog;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(
        protected PropertyService $propertyService
    ) {}

    public function index(Request $request)
    {
        $search   = $request->get('search');
        $category = $request->get('category');
        $status   = $request->get('status');

        $items      = $this->propertyService->getFiltered($search, $category, $status);
        $categories = $this->propertyService->getCategories();

        return view('property.index', compact('items', 'categories', 'search', 'category', 'status'));
    }

    public function create()
    {
        return view('property.create');
    }

    public function store(StorePropertyRequest $request)
    {
        $item = $this->propertyService->createWithStockLog($request->validated());

        return redirect()->route('property.index')
            ->with('success', "Property item '{$item->item_name}' created successfully.");
    }

    public function show(Property $property)
    {
        $property->load([
            'creator',
            'updater',
            'stockLogs' => function ($q) {
                $q->orderBy('date_created', 'desc')->limit(50);
            }
        ]);

        return view('property.show', ['item' => $property]);
    }

    public function edit(Property $property)
    {
        return view('property.edit', ['item' => $property]);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $data = $request->validated();
        $data['unit_cost']       = $data['unit_cost'] ?? 0;
        $data['current_stock']   = $data['current_stock'] ?? 0;
        $data['last_updated_by'] = auth()->id();
        $data['date_updated']    = now();

        $property->update($data);

        return redirect()->route('property.index')
            ->with('success', "Property item '{$property->item_name}' updated successfully.");
    }

    public function destroy(Property $property)
    {
        $name = $property->item_name;
        $property->delete();

        return redirect()->route('property.index')
            ->with('success', "Property item '{$name}' deleted successfully.");
    }

    public function movements(Property $property)
    {
        $logs = PropertyStockLog::where('property_id', $property->property_id)
            ->orderBy('date_created', 'desc')
            ->paginate(20);

        return view('property.show', [
            'item' => $property,
            'logs' => $logs,
        ]);
    }

    public function stockAdjust(Request $request, Property $property)
    {
        $request->validate([
            'type'     => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:255',
        ]);

        $this->propertyService->updateStock(
            $property->property_id,
            $request->quantity,
            $request->type,
            $request->notes ?? ''
        );

        return redirect()->route('property.show', $property)
            ->with('success', "Stock adjusted for '{$property->item_name}'.");
    }
}
