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
            'status', 'campus', 'employment_type', 'created_at',
        ]);

        $requests = DB::table('property_request')
            ->where('user_id', $id)
            ->orderByDesc('date_requested')
            ->get();

        $checkedOut = $requests->filter(fn ($r) => empty($r->date_return))->values();
        $history = $requests;

        return response()->json([
            'employee' => $employee,
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
        ]);

        User::findOrFail($id);

        $updated = DB::table('property_request')
            ->where('user_id', $id)
            ->whereIn('property_id', $data['items'])
            ->whereNull('date_return')
            ->update([
                'date_return' => now()->toDateString(),
                'status'      => $data['status'] ?? 'In Storage',
            ]);

        return response()->json(['success' => true, 'checked_in' => $updated]);
    }
}
