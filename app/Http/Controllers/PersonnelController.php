<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonnelController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $department = trim((string) $request->get('department'));
        $status = trim((string) $request->get('status'));

        $query = User::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere(DB::raw("CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))"), 'like', "%{$search}%")
                        ->orWhere('eid', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('position', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->when($department !== '', fn ($q) => $q->where('department', $department))
            ->when($status !== '', fn ($q) => $q->where('status', $status))
            ->orderBy('last_name')
            ->orderBy('first_name');

        $employees = $query->paginate(12)->withQueryString();

        $departments = User::select('department')
            ->whereNotNull('department')->where('department', '!=', '')
            ->distinct()->orderBy('department')->pluck('department');
        $statuses = User::select('status')
            ->whereNotNull('status')->where('status', '!=', '')
            ->distinct()->orderBy('status')->pluck('status');

        return view('personnel.index', compact('employees', 'departments', 'statuses', 'search', 'department', 'status'));
    }

    public function show(int $id)
    {
        $employee = User::findOrFail($id, [
            'id', 'eid', 'title', 'first_name', 'middle_name', 'last_name', 'suffix',
            'academic_title', 'email', 'department', 'position', 'user_type',
            'status', 'campus', 'employment_type', 'profile', 'created_at',
        ]);

        $requests = DB::table('property_request')
            ->where('user_id', $id)
            ->orderByDesc('date_requested')
            ->get();

        $checkedOut = $requests->filter(fn ($r) => empty($r->date_return))->values();
        $history = $requests;

        return response()->json([
            'employee' => array_merge($employee->toArray(), ['profile_url' => $employee->profile_url]),
            'checked_out' => $checkedOut,
            'attachments' => [],
            'notes' => [],
            'history' => $history,
        ]);
    }

    public function checkIn(Request $request, int $id)
    {
        $data = $request->validate([
            'items'    => ['required', 'array', 'min:1'],
            'items.*'  => ['integer'],
            'status'   => ['nullable', 'string', 'max:50'],
            'comments' => ['nullable', 'string', 'max:1000'],
            'print_receipt' => ['nullable', 'boolean'],
        ]);

        User::findOrFail($id);
        $now = now();

        $updated = DB::transaction(function () use ($data, $id, $now) {
            // Fetch the open check-out requests being returned (for item names/quantities)
            $requests = DB::table('property_request')
                ->where('user_id', $id)
                ->whereIn('property_id', $data['items'])
                ->whereNull('date_return')
                ->get();

            // Record check-in history, one row per returned item
            foreach ($requests as $req) {
                DB::table('check_in')->insert([
                    'items_to_check_in' => $req->item_name,
                    'inventory_id'      => $req->property_id,
                    'personnel_id'      => $id,
                    'quantity'          => (int) $req->quantity_requested,
                    'status'            => $data['status'] ?? 'Returned',
                    'location'          => $req->department_unit ?? 'All location',
                    'comments'          => $data['comments'] ?? null,
                    'print_receipt'     => ! empty($data['print_receipt']),
                    'checked_in_by'     => trim((string) (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '')) ?: (auth()->user()->username ?? 'system'),
                    'created_at'        => $now,
                    'updated_at'        => $now,
                ]);
            }

            // Close out the property_request rows (return date + new status)
            return DB::table('property_request')
                ->where('user_id', $id)
                ->whereIn('property_id', $data['items'])
                ->whereNull('date_return')
                ->update([
                    'date_return' => $now->toDateString(),
                    'status'      => $data['status'] ?? 'In Storage',
                ]);
        });

        return response()->json(['success' => true, 'checked_in' => $updated]);
    }

    public function assets()
    {
        $checkedOutIds = DB::table('property_request')
            ->whereNull('date_return')
            ->pluck('property_id')
            ->all();

        $items = DB::table('property_inventory')
            ->where('status', 'Active')
            ->where('current_stock', '>', 0)
            ->orderBy('item_name')
            ->get(['inventory_id', 'item_name', 'brand', 'category', 'unit', 'current_stock'])
            ->map(function ($i) use ($checkedOutIds) {
                return [
                    'property_id'    => $i->inventory_id,
                    'item_name'      => $i->item_name,
                    'brand'          => $i->brand,
                    'category'       => $i->category,
                    'unit'           => $i->unit,
                    'available'      => (int) $i->current_stock,
                    'already_out'    => in_array($i->inventory_id, $checkedOutIds, true),
                ];
            })
            ->values();

        return response()->json(['items' => $items]);
    }

    public function checkOut(Request $request, int $id)
    {
        $data = $request->validate([
            'items'    => ['required', 'array', 'min:1'],
            'items.*.property_id' => ['required', 'integer'],
            'items.*.quantity'    => ['required', 'integer', 'min:1'],
            'status'   => ['nullable', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:100'],
            'comments' => ['nullable', 'string', 'max:1000'],
            'print_receipt' => ['nullable', 'boolean'],
        ]);

        User::findOrFail($id);
        $now = now();

        DB::transaction(function () use ($data, $id, $now) {
            foreach ($data['items'] as $item) {
                $asset = DB::table('property_inventory')
                    ->where('inventory_id', $item['property_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $asset || (int) $asset->current_stock < (int) $item['quantity']) {
                    throw new \RuntimeException('Insufficient stock or item not found.');
                }

                DB::table('property_request')->insert([
                    'property_id'         => $asset->inventory_id,
                    'user_id'             => $id,
                    'date_requested'      => $now->toDateTimeString(),
                    'category'            => $asset->category,
                    'item_name'           => $asset->item_name,
                    'brand'               => $asset->brand,
                    'quantity_requested'  => $item['quantity'],
                    'request_type'        => 'OUT',
                    'tagging'             => $data['status'] ?? 'Out',
                    'status'              => $data['status'] ?? 'Out',
                    'department_unit'     => $data['location'] ?? null,
                    'remarks'             => $data['comments'] ?? null,
                ]);

                DB::table('check_out')->insert([
                    'inventory_id'        => $asset->inventory_id,
                    'personnel_id'        => $id,
                    'quantity'            => (int) $item['quantity'],
                    'status'              => $data['status'] ?? 'Out',
                    'location_department' => $data['location'] ?? '',
                    'comments'            => $data['comments'] ?? null,
                    'print_receipt'       => ! empty($data['print_receipt']),
                    'checked_out_by'      => trim((string) (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '')) ?: (auth()->user()->username ?? 'system'),
                    'checked_out_at'      => $now,
                ]);

                DB::table('property_inventory')
                    ->where('inventory_id', $asset->inventory_id)
                    ->decrement('current_stock', (int) $item['quantity']);
            }
        });

        return response()->json(['success' => true]);
    }
}
