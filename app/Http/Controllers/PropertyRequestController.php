<?php

namespace App\Http\Controllers;

use App\Models\PropertyRequest;
use App\Services\PropertyRequestService;
use App\Http\Requests\StorePropertyFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyRequestController extends Controller
{
    public function __construct(
        protected PropertyRequestService $propertyRequestService
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        $department = $request->get('department');

        $propertyRequests = $this->propertyRequestService->getFiltered($search, $status, $department);

        $counts = PropertyRequest::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('property-requests.index', compact('propertyRequests', 'search', 'status', 'department', 'counts'));
    }

    public function create()
    {
        return view('property-requests.create');
    }

    public function store(StorePropertyFormRequest $request)
    {
        $this->propertyRequestService->submitRequest($request->validated());

        return redirect()->route('property-requests.index')
            ->with('success', 'Property request submitted successfully.');
    }

    public function show($id)
    {
        $propertyRequest = PropertyRequest::with([
            'user', 'noter', 'checker', 'verifier', 'issuer', 'approver'
        ])->findOrFail($id);

        $qrCode = $propertyRequest->qrcode
            ? \SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->errorCorrection('H')->generate($propertyRequest->qrcode)
            : null;

        return view('property-requests.show', compact('propertyRequest', 'qrCode'));
    }

    public function edit($id)
    {
        $propertyRequest = PropertyRequest::findOrFail($id);

        if (!in_array($propertyRequest->status, ['Pending', 'Rejected'])) {
            return back()->with('error', 'Only pending or rejected requests can be edited.');
        }

        return view('property-requests.edit', compact('propertyRequest'));
    }

    public function update(StorePropertyFormRequest $request, $id)
    {
        $propertyRequest = PropertyRequest::findOrFail($id);

        if (!in_array($propertyRequest->status, ['Pending', 'Rejected'])) {
            return back()->with('error', 'Only pending or rejected requests can be updated.');
        }

        $propertyRequest->update($request->validated());

        return redirect()->route('property-requests.index')
            ->with('success', 'Property request updated successfully.');
    }

    public function destroy($id)
    {
        PropertyRequest::findOrFail($id)->delete();

        return redirect()->route('property-requests.index')
            ->with('success', 'Property request deleted successfully.');
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'step' => 'required|in:noted,checked,verified,approved,issued',
        ]);

        $propertyRequest = PropertyRequest::findOrFail($id);

        $allowedSteps = [
            'Pending'  => 'noted',
            'Noted'    => 'checked',
            'Checked'  => 'verified',
            'Verified' => 'approved',
            'Approved' => 'issued',
        ];

        $expectedStep = $allowedSteps[$propertyRequest->status] ?? null;

        if ($request->step !== $expectedStep) {
            return back()->with('error', 'Invalid approval step. Expected: ' . ($expectedStep ?? 'N/A'));
        }

        $this->propertyRequestService->approveStep($id, $request->step, Auth::id());

        $label = ucfirst($request->step);

        return redirect()->route('property-requests.show', $id)
            ->with('success', "Property request {$label} successfully.");
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->propertyRequestService->rejectRequest($id, Auth::id(), $request->reason);

        return redirect()->route('property-requests.show', $id)
            ->with('error', 'Property request rejected.');
    }
}
