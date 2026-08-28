<?php

namespace App\Http\Controllers;

use App\Models\Canvass;
use App\Models\CanvassItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CanvassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Canvass::with(['items', 'creator'])->latest('canvass_date');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('canvass_id', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('items', function ($q2) use ($search) {
                      $q2->where('item_description', 'like', "%{$search}%")
                         ->orWhere('supplier_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $canvasses = $query->paginate(15);

        return view('canvass.index', compact('canvasses', 'search', 'status'));
    }

    public function create()
    {
        return view('canvass.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'canvass_date' => 'required|date',
            'status' => 'required|in:Canvassed,Completed,Approved,Cancelled',
            'canvassed_by' => 'nullable|integer',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_number' => 'required|integer',
            'items.*.supplier_name' => 'required|string|max:255',
            'items.*.department' => 'nullable|string|max:255',
            'items.*.campus' => 'nullable|string|max:255',
            'items.*.item_description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $item['total_cost'] = $item['quantity'] * $item['unit_cost'];
                $totalAmount += $item['total_cost'];
            }

            $validated['total_amount'] = $totalAmount;
            $validated['created_by'] = auth()->id();

            $canvass = Canvass::create($validated);

            foreach ($validated['items'] as $item) {
                $item['canvass_id'] = $canvass->canvass_id;
                CanvassItem::create($item);
            }

            return redirect()->route('canvass.index')
                ->with('success', 'Canvass created successfully.');
        });
    }

    public function show(int $id)
    {
        $canvass = Canvass::with('items')->findOrFail($id);
        return view('canvass.show', compact('canvass'));
    }

    public function edit(int $id)
    {
        $canvass = Canvass::with('items')->findOrFail($id);
        return view('canvass.edit', compact('canvass'));
    }

    public function update(Request $request, int $id)
    {
        $canvass = Canvass::findOrFail($id);

        $validated = $request->validate([
            'canvass_date' => 'required|date',
            'status' => 'required|in:Canvassed,Completed,Approved,Cancelled',
            'canvassed_by' => 'nullable|integer',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.canvass_item_id' => 'nullable|integer',
            'items.*.item_number' => 'required|integer',
            'items.*.supplier_name' => 'required|string|max:255',
            'items.*.department' => 'nullable|string|max:255',
            'items.*.campus' => 'nullable|string|max:255',
            'items.*.item_description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($canvass, $validated) {
            $totalAmount = 0;
            foreach ($validated['items'] as $item) {
                $item['total_cost'] = $item['quantity'] * $item['unit_cost'];
                $totalAmount += $item['total_cost'];
            }

            $canvass->update([
                'canvass_date' => $validated['canvass_date'],
                'status' => $validated['status'],
                'canvassed_by' => $validated['canvassed_by'],
                'notes' => $validated['notes'],
                'total_amount' => $totalAmount,
            ]);

            $existingIds = $canvass->items->pluck('canvass_item_id')->toArray();
            $submittedIds = [];

            foreach ($validated['items'] as $itemData) {
                $itemData['canvass_id'] = $canvass->canvass_id;
                $itemData['total_cost'] = $itemData['quantity'] * $itemData['unit_cost'];

                if (!empty($itemData['canvass_item_id']) && in_array($itemData['canvass_item_id'], $existingIds)) {
                    CanvassItem::where('canvass_item_id', $itemData['canvass_item_id'])->update($itemData);
                    $submittedIds[] = $itemData['canvass_item_id'];
                } else {
                    $newItem = CanvassItem::create($itemData);
                    $submittedIds[] = $newItem->canvass_item_id;
                }
            }

            CanvassItem::where('canvass_id', $canvass->canvass_id)
                ->whereNotIn('canvass_item_id', $submittedIds)
                ->delete();

            return redirect()->route('canvass.index')
                ->with('success', 'Canvass updated successfully.');
        });
    }

    public function destroy(int $id)
    {
        $canvass = Canvass::findOrFail($id);
        $canvass->items()->delete();
        $canvass->delete();

        return redirect()->route('canvass.index')
            ->with('success', 'Canvass deleted successfully.');
    }

    public function editData(int $id)
    {
        $canvass = Canvass::with('items')->findOrFail($id);
        $canvassedBy = $canvass->canvassed_by ? \App\Models\User::find($canvass->canvassed_by) : null;
        
        return response()->json([
            'canvass' => [
                'canvass_id' => $canvass->canvass_id,
                'canvass_date' => $canvass->canvass_date,
                'status' => $canvass->status,
                'canvassed_by' => $canvass->canvassed_by,
                'canvassed_by_name' => $canvassedBy ? $canvassedBy->display_name : null,
                'notes' => $canvass->notes,
            ],
            'items' => $canvass->items->map(function ($item) {
                return [
                    'canvass_item_id' => $item->canvass_item_id,
                    'item_number' => $item->item_number,
                    'supplier_name' => $item->supplier_name,
                    'department' => $item->department,
                    'campus' => $item->campus,
                    'item_description' => $item->item_description,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'total_cost' => $item->total_cost,
                ];
            }),
        ]);
    }

    public function viewData(int $id)
    {
        $canvass = Canvass::with('items')->findOrFail($id);
        $canvassedBy = $canvass->canvassed_by ? \App\Models\User::find($canvass->canvassed_by) : null;
        
        return response()->json([
            'canvass' => [
                'canvass_id' => $canvass->canvass_id,
                'canvass_date' => $canvass->canvass_date,
                'status' => $canvass->status,
                'canvassed_by' => $canvass->canvassed_by,
                'canvassed_by_name' => $canvassedBy ? $canvassedBy->display_name : null,
                'notes' => $canvass->notes,
                'total_amount' => $canvass->total_amount,
            ],
            'items' => $canvass->items->map(function ($item) {
                return [
                    'canvass_item_id' => $item->canvass_item_id,
                    'item_number' => $item->item_number,
                    'supplier_name' => $item->supplier_name,
                    'department' => $item->department,
                    'campus' => $item->campus,
                    'item_description' => $item->item_description,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'total_cost' => $item->total_cost,
                ];
            }),
        ]);
    }
}