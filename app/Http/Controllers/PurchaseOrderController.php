<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $status = trim((string) $request->get('status'));

        $q = PurchaseOrder::withCount('items');

        if ($search !== '') {
            $q = $q->where(function ($q) use ($search) {
                $q->where('po_number', 'like', '%' . $search . '%')
                  ->orWhere('supplier_name', 'like', '%' . $search . '%');
            });
        }
        if ($status !== '') {
            $q = $q->where('status', $status);
        }

        $orders = $q->orderBy('po_id', 'desc')->paginate(15);

        $statuses = PurchaseOrder::distinct()->orderBy('status')->pluck('status');
        $totalOrders = PurchaseOrder::count();
        $pendingCount = PurchaseOrder::where('status', 'Pending')->orWhere('status', 'Draft')->count();
        $totalValue = (float) PurchaseOrder::sum('total_amount');

        return view('purchase-orders.index',
            compact('orders', 'search', 'status', 'statuses', 'totalOrders', 'pendingCount', 'totalValue'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();

        // Next PO number = max + 1 (numeric portion).
        $max = (int) DB::table('purchase_orders')->max('po_number');
        $nextNumber = (string) ($max + 1);

        return view('purchase-orders.create', compact('suppliers', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'po_date'          => 'required|date',
            'supplier_id'      => 'required|integer',
            'payment_method'   => 'required|string|max:50',
            'payment_details'  => 'nullable|string|max:255',
            'cash_amount'      => 'nullable|numeric|min:0',
            'prepared_by'      => 'required|string|max:255',
            'checked_by'       => 'nullable|string|max:255',
            'approved_by'      => 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:1000',
            'items'            => 'required|array|min:1',
        ], [
            'items.required' => 'Add at least one item line.',
        ]);

        $supplier = Supplier::findOrFail($v['supplier_id']);

        $max = (int) DB::table('purchase_orders')->max('po_number');

        return DB::transaction(function () use ($v, $supplier, $max) {
            $subtotal = 0.0;
            $lines = [];
            foreach ($v['items'] as $i => $item) {
                $qty = (float) ($item['quantity'] ?? 0);
                $cost = (float) ($item['unit_cost'] ?? 0);
                $line = $qty * $cost;
                $subtotal += $line;
                $lines[] = [
                    'item_number'      => (string) ($item['item_number'] ?? ($i + 1)),
                    'item_description' => trim((string) ($item['item_description'] ?? '')),
                    'quantity'         => $qty,
                    'unit_cost'        => $cost,
                    'line_total'       => $line,
                ];
            }

            $cash = (float) ($v['cash_amount'] ?? 0);
            $total = $subtotal - ($v['payment_method'] === 'Cash' ? $cash : 0);

            $order = PurchaseOrder::create([
                'po_number'        => (string) ($max + 1),
                'po_date'          => $v['po_date'],
                'supplier_name'    => $supplier->supplier_name,
                'supplier_address' => $supplier->address,
                'payment_method'   => $v['payment_method'],
                'payment_details'  => $v['payment_details'] ?? null,
                'cash_amount'      => $cash,
                'subtotal'         => round($subtotal, 2),
                'total_amount'     => round($total, 2),
                'status'           => 'Pending',
                'prepared_by'      => $v['prepared_by'],
                'checked_by'       => $v['checked_by'] ?? null,
                'approved_by'      => $v['approved_by'] ?? null,
                'notes'            => $v['notes'] ?? null,
                'created_by'       => Auth::id(),
            ]);

            foreach ($lines as $ln) {
                PurchaseOrderItem::create($ln + ['po_id' => $order->po_id]);
            }

            return redirect()->route('purchase-orders.index')
                ->with('success', 'Purchase order PO-' . $order->po_number . ' created successfully.');
        });
    }
}
