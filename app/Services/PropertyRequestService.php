<?php

namespace App\Services;

use App\Models\PropertyRequest;
use Illuminate\Support\Facades\Auth;

class PropertyRequestService
{
    public function getFiltered($search = null, $status = null, $department = null)
    {
        $query = PropertyRequest::with(['user', 'noter', 'checker', 'verifier', 'issuer', 'approver']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                  ->orWhere('reason_for_transfer', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhere('property_id', 'like', "%{$search}%");
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

        if (empty($data['qrcode'])) {
            $cut = fn ($v, $n) => mb_substr(trim((string) $v), 0, $n);
            $payload = [
                'type'       => 'PROPERTY_REQUEST',
                'item'       => $cut($data['item_name'] ?? '', 60),
                'qty'        => $data['quantity_requested'] ?? 1,
                'department' => $cut($data['department_unit'] ?? '', 30),
                'reason'     => $cut($data['reason_for_transfer'] ?? '', 60),
                'requestor'  => $cut(Auth::user()->display_name ?? '', 30),
            ];
            $data['qrcode'] = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $request = PropertyRequest::create($data);

        // Re-encode with the real ID once available (still within 255 chars)
        $payload['id'] = $request->property_id;
        $request->update(['qrcode' => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);

        return $request;
    }

    public function approveStep($id, $step, $userId)
    {
        $request = PropertyRequest::findOrFail($id);

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
        $request = PropertyRequest::findOrFail($id);
        $request->status = 'Rejected';
        $request->remarks = $reason;
        $request->save();

        return $request;
    }
}
