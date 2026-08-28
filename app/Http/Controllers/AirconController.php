<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAirconRequest;
use App\Http\Requests\UpdateAirconRequest;
use App\Models\Aircon;
use App\Services\AirconService;
use Illuminate\Http\Request;

class AirconController extends Controller
{
    public function __construct(
        protected AirconService $airconService
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');

        $items = $this->airconService->getFiltered($search, $category, $status);
        $categories = $this->airconService->getCategories();

        return view('aircon.index', compact('items', 'categories', 'search', 'category', 'status'));
    }

    public function create()
    {
        return view('aircon.create');
    }

    public function store(StoreAirconRequest $request)
    {
        $data = $request->validated();
        $data['current_stock'] = $data['current_stock'] ?? 0;
        $data['status'] = $data['status'] ?? 'Active';
        $data['created_by'] = auth()->id();
        $data['date_created'] = now();

        $item = Aircon::create($data);

        return redirect()->route('aircon.index')
            ->with('success', "Aircon unit '{$item->item_name}' created successfully.");
    }

    public function show(int $id)
    {
        $item = Aircon::with(['creator', 'updater'])->findOrFail($id);
        return view('aircon.show', compact('item'));
    }

    public function edit(int $id)
    {
        $item = Aircon::findOrFail($id);
        return view('aircon.edit', compact('item'));
    }

    public function update(UpdateAirconRequest $request, int $id)
    {
        $item = Aircon::findOrFail($id);
        $data = $request->validated();
        $data['unit_cost'] = $data['unit_cost'] ?? 0;
        $data['current_stock'] = $data['current_stock'] ?? 0;
        $data['last_updated_by'] = auth()->id();
        $data['date_updated'] = now();
        $item->update($data);

        return redirect()->route('aircon.index')
            ->with('success', "Aircon unit '{$item->item_name}' updated successfully.");
    }

    public function destroy(int $id)
    {
        $item = Aircon::findOrFail($id);
        $name = $item->item_name;
        $item->delete();

        return redirect()->route('aircon.index')
            ->with('success', "Aircon unit '{$name}' deleted successfully.");
    }
}
