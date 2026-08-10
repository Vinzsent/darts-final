<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use App\Models\Supplier;
use App\Services\ProcurementService;
use App\Http\Requests\StoreProcurementRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    protected ProcurementService $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $procurements = $this->procurementService->getFiltered($search, $status);

        $statuses = Procurement::select('status')->distinct()->whereNotNull('status')->pluck('status');

        return view('procurement.index', compact('procurements', 'search', 'status', 'statuses'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('supplier_name')->get();
        return view('procurement.create', compact('suppliers'));
    }

    public function store(StoreProcurementRequest $request)
    {
        $data = $request->validated();
        $data['amount'] = $data['amount'] ?? ($data['quantity'] * $data['unit_price']);
        $data['status'] = $data['status'] ?? 'Pending';

        Procurement::create($data);

        return redirect()->route('procurement.index')
            ->with('success', 'Procurement record created successfully.');
    }

    public function show(int $id)
    {
        $procurement = $this->procurementService->getStatusHistory($id);
        return view('procurement.show', compact('procurement'));
    }

    public function edit(int $id)
    {
        $procurement = Procurement::findOrFail($id);
        $suppliers = Supplier::orderBy('supplier_name')->get();
        return view('procurement.edit', compact('procurement', 'suppliers'));
    }

    public function update(StoreProcurementRequest $request, int $id)
    {
        $procurement = Procurement::findOrFail($id);
        $data = $request->validated();
        $data['amount'] = $data['amount'] ?? ($data['quantity'] * $data['unit_price']);

        $procurement->update($data);

        return redirect()->route('procurement.index')
            ->with('success', 'Procurement record updated successfully.');
    }

    public function destroy(int $id)
    {
        $procurement = Procurement::findOrFail($id);
        $procurement->delete();

        return redirect()->route('procurement.index')
            ->with('success', 'Procurement record deleted successfully.');
    }

    public function markReceived(int $id)
    {
        $procurement = Procurement::findOrFail($id);
        $procurement->update(['status' => 'Received']);

        return redirect()->route('procurement.show', $id)
            ->with('success', 'Procurement marked as received.');
    }
}
