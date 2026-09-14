<?php

namespace App\Http\Controllers;

use App\Models\Aircon;
use App\Models\AirconImage;
use App\Models\Supplier;
use App\Services\AirconService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('aircon.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['date_created'] = now();
        $data['date_updated'] = now();
        $data['created_by'] = Auth::id();

        $item = DB::transaction(function () use ($request, $data) {
            $aircon = Aircon::create($data);
            $this->storeImages($request, $aircon->aircon_id);

            return $aircon;
        });

        return redirect()->route('aircon.show', $item->aircon_id)
            ->with('success', 'Aircon unit saved successfully.');
    }

    public function show(int $id)
    {
        $item = Aircon::with(['images', 'maintenance'])->findOrFail($id);

        return view('aircon.show', compact('item'));
    }
public function edit(int $id)
    {
        $item = Aircon::with('images')->findOrFail($id);
        $suppliers = Supplier::orderBy('supplier_name')->get();

        return view('aircon.edit', compact('item', 'suppliers'));
    }

    public function update(Request $request, int $id)
    {
        $item = Aircon::with('images')->findOrFail($id);
        $data = $this->validated($request);
        $data['date_updated'] = now();

        DB::transaction(function () use ($request, $item, $data) {
            $item->update($data);

            // Remove images the user flagged.
            $remove = array_map(fn($v) => (int) $v, (array) $request->get('remove_images', []));
            if (count($remove) > 0) {
                foreach ($item->images as $img) {
                    if (in_array($img->id, $remove)) {
                        if ($img->image_path && str_starts_with($img->image_path, 'aircons/')) {
                            Storage::disk('public')->delete($img->image_path);
                        }
                        $img->delete();
                    }
                }
            }

            $this->storeImages($request, $item->aircon_id, $item);
        });

        return redirect()->route('aircon.show', $id)
            ->with('success', 'Aircon unit updated successfully.');
    }

    public function destroy(int $id)
    {
        $item = Aircon::with('images')->findOrFail($id);

        DB::transaction(function () use ($item) {
            foreach ($item->images as $img) {
                if ($img->image_path && str_starts_with($img->image_path, 'aircons/')) {
                    Storage::disk('public')->delete($img->image_path);
                }
            }
            $item->images()->delete();
            $item->delete();
        });

        return redirect()->route('aircon.index')
            ->with('success', 'Aircon unit deleted successfully.');
    }

    private function storeImages(Request $request, int $airconId, ?Aircon $existing = null)
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $incoming = $request->file('images');
        $uploaded = is_array($incoming) ? $incoming : [$incoming];
        $uploaded = array_values(array_filter($uploaded, fn($f) => $f !== null));
        if (count($uploaded) === 0) {
            return;
        }

        $remaining = 5 - ($existing ? $existing->images->count() : 0);
        if ($remaining <= 0) {
            return;
        }

        $i = 0;
        foreach ($uploaded as $u) {
            if ($i >= $remaining) {
                break;
            }
            $path = $u->store('aircons', 'public');
            if ($path) {
                AirconImage::create([
                    'aircon_id'  => $airconId,
                    'image_path' => $path,
                    'created_at' => now(),
                ]);
                $i++;
            }
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'item_number'       => 'nullable|string|max:100',
            'category'          => 'nullable|string|max:50',
            'brand'             => 'nullable|string|max:50',
            'model'             => 'nullable|string|max:50',
            'type'              => 'nullable|string|max:50',
            'capacity'          => 'nullable|string|max:50',
            'serial_number'     => 'nullable|string|max:100',
            'location'          => 'nullable|string|max:100',
            'campus'            => 'nullable|string|max:255',
            'area'              => 'nullable|string|max:255',
            'bldg'              => 'nullable|string|max:255',
            'status'            => 'nullable|string|max:50',
            'purchase_date'     => 'nullable|string|max:255',
            'warranty_expiry'   => 'nullable|date',
            'installation_date' => 'nullable|date',
            'maintenance_schedule' => 'nullable|string|max:100',
            'energy_efficiency_rating' => 'nullable|string|max:50',
            'power_consumption' => 'nullable|numeric',
            'notes'             => 'nullable|string',
            'purchase_price'    => 'nullable|numeric|min:0',
            'receiver'          => 'nullable|string|max:100',
            'supplier_id'       => 'nullable|integer',
            'images'            => 'nullable|array|max:5',
        ]);
    }
}
