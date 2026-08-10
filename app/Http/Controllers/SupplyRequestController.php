<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Services\SupplyRequestService;
use App\Http\Requests\StoreSupplyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplyRequestController extends Controller
{
    public function __construct(
        protected SupplyRequestService $supplyRequestService
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $department = $request->get('department');

        $supplyRequests = $this->supplyRequestService->getFiltered($search, $status, $department);

        $counts = SupplyRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('supply-requests.index', compact('supplyRequests', 'search', 'status', 'department', 'counts'));
    }

    public function create()
    {
        return view('supply-requests.create');
    }

    public function store(StoreSupplyRequest $request)
    {
        $this->supplyRequestService->submitRequest($request->validated());

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request submitted successfully.');
    }

    public function show($id)
    {
        $supplyRequest = SupplyRequest::with([
            'user', 'noter', 'checker', 'verifier', 'issuer', 'approver'
        ])->findOrFail($id);

        return view('supply-requests.show', compact('supplyRequest'));
    }

    public function edit($id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);

        if (!in_array($supplyRequest->status, ['Pending', 'Rejected'])) {
            return back()->with('error', 'Only pending or rejected requests can be edited.');
        }

        return view('supply-requests.edit', compact('supplyRequest'));
    }

    public function update(StoreSupplyRequest $request, $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);

        if (!in_array($supplyRequest->status, ['Pending', 'Rejected'])) {
            return back()->with('error', 'Only pending or rejected requests can be updated.');
        }

        $supplyRequest->update($request->validated());

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request updated successfully.');
    }

    public function destroy($id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);
        $supplyRequest->delete();

        return redirect()->route('supply-requests.index')
            ->with('success', 'Supply request deleted successfully.');
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'step' => 'required|in:noted,checked,verified,approved,issued',
        ]);

        $supplyRequest = SupplyRequest::findOrFail($id);

        $allowedSteps = [
            'Pending'  => 'noted',
            'Noted'    => 'checked',
            'Checked'  => 'verified',
            'Verified' => 'approved',
            'Approved' => 'issued',
        ];

        $expectedStep = $allowedSteps[$supplyRequest->status] ?? null;

        if ($request->step !== $expectedStep) {
            return back()->with('error', 'Invalid approval step. Expected: ' . ($expectedStep ?? 'N/A'));
        }

        $this->supplyRequestService->approveStep($id, $request->step, Auth::id());

        $label = ucfirst($request->step);

        return redirect()->route('supply-requests.show', $id)
            ->with('success', "Supply request {$label} successfully.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->supplyRequestService->rejectRequest($id, Auth::id(), $request->reason);

        return redirect()->route('supply-requests.show', $id)
            ->with('error', 'Supply request rejected.');
    }
}
