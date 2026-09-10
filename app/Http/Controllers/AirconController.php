<?php

namespace App\Http\Controllers;

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
        $search = trim((string) $request->get('search'));
        $brand = trim((string) $request->get('brand'));
        $status = trim((string) $request->get('status'));

        $items = $this->airconService->getFiltered($search, $brand, $status);
        $brands = $this->airconService->getBrands();
        $statuses = $this->airconService->getStatuses();
        $stats = $this->airconService->stats();

        return view('aircon.index',
            compact('items', 'brands', 'statuses', 'search', 'brand', 'status', 'stats'));
    }

    public function show(int $id)
    {
        $item = Aircon::with(['images', 'maintenance'])->findOrFail($id);

        return view('aircon.show', compact('item'));
    }
}
