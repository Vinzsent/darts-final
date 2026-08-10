<?php

namespace App\Services;

use App\Models\SupplyRequest;
use Illuminate\Support\Facades\Auth;

class SupplyRequestService
{
    public function getFiltered($search = null, $status = null, $department = null)
    {
        $query = SupplyRequest::with([
            'user', 'noter', 'checker', 'verifier', 'issuer', 'approver'
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhere('request_id', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        if ($department) {
            $query->where('department_unit', $department);
        }

        return $query->orderBy('date_requested', 'desc')->paginate(15);
    }

    public function submitRequest(array $data)
    {
        $data['user_id'] = Auth::id();
        $data['date_requested'] = now();
        $data['status'] = 'Pending';
        $data['amount'] = $data['amount'] ?? 0;
        $data['quality_issued'] = $data['quality_issued'] ?? '';

        return SupplyRequest::create($data);
    }

    public function approveStep($id, $step, $userId)
    {
        $request = SupplyRequest::findOrFail($id);

        $field = $step . '_by';
        $dateField = $step . '_date';

        $request->$field = $userId;
        $request->$dateField = now();

        $statusMap = [
            'noted'    => 'Noted',
            'checked'  => 'Checked',
            'verified' => 'Verified',
            'approved' => 'Approved',
            'issued'   => 'Issued',
        ];

        if (isset($statusMap[$step])) {
            $request->status = $statusMap[$step];
        }

        $request->save();

        return $request;
    }

    public function rejectRequest($id, $userId, $reason)
    {
        $request = SupplyRequest::findOrFail($id);
        $request->status = 'Rejected';
        $request->remarks = $reason;
        $request->save();

        return $request;
    }

    public function getPendingForRole($role)
    {
        $statusMap = [
            'Supply In-charge'  => 'Pending',
            'admin'             => 'Pending',
            'Purchasing Officer' => 'Noted',
            'Purchasing Staff'  => 'Checked',
        ];

        $status = $statusMap[$role] ?? 'Pending';

        return SupplyRequest::where('status', $status)
            ->orderBy('date_requested', 'asc')
            ->get();
    }
}
