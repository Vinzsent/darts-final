<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentIssuanceController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->get('search');
        $status     = $request->get('status', 'Approved');
        $department = $request->get('department');

        $query = SupplyRequest::with(['user', 'issuer'])
            ->whereIn('status', ['Approved', 'Issued', 'Pending']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('department_unit', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('item_number', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($department) {
            $query->where('department_unit', $department);
        }

        $requests    = $query->orderByRaw("FIELD(status,'Approved','Pending','Issued')")
                             ->orderBy('date_needed', 'asc')
                             ->paginate(15)
                             ->appends($request->only(['search', 'status', 'department']));

        $departments = SupplyRequest::whereNotNull('department_unit')
                                    ->distinct()
                                    ->pluck('department_unit');

        $statuses    = ['Approved', 'Pending', 'Issued'];

        // Summary counts
        $pendingCount  = SupplyRequest::where('status', 'Pending')->count();
        $approvedCount = SupplyRequest::where('status', 'Approved')->count();
        $issuedCount   = SupplyRequest::where('status', 'Issued')->count();

        return view('assignment-issuance.index', compact(
            'requests', 'search', 'status', 'department',
            'departments', 'statuses',
            'pendingCount', 'approvedCount', 'issuedCount'
        ));
    }

    public function issue(Request $request, int $id)
    {
        $supplyRequest = SupplyRequest::findOrFail($id);

        if ($supplyRequest->status !== 'Approved') {
            return back()->with('error', 'Only approved requests can be issued.');
        }

        $supplyRequest->update([
            'status'      => 'Issued',
            'issued_by'   => Auth::id(),
            'issued_date' => now()->toDateString(),
        ]);

        return back()->with('success', "Request #{$supplyRequest->item_number} has been issued successfully.");
    }

    public function show(int $id)
    {
        $supplyRequest = SupplyRequest::with(['user', 'issuer', 'approver'])->findOrFail($id);
        return view('assignment-issuance.show', compact('supplyRequest'));
    }
}
