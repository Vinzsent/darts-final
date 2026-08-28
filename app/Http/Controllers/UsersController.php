<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public static function userTypes(): array
    {
        $types = User::query()
            ->distinct()
            ->whereNotNull('user_type')
            ->where('user_type', '!=', '')
            ->pluck('user_type')
            ->values()
            ->all();

        return count($types) > 0 ? $types : ['Admin'];
    }

    public static function positions(): array
    {
        $positions = User::query()
            ->distinct()
            ->whereNotNull('position')
            ->where('position', '!=', '')
            ->pluck('position')
            ->values()
            ->all();

        return count($positions) > 0 ? $positions : ['Admin', 'College Instructor', 'Staff'];
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $position = $request->get('position');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($query) => $query->where('status', strtoupper($status)))
            ->when($position, fn($query) => $query->where('position', $position))
            ->orderBy('last_name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search', 'status', 'position'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'ACTIVE';

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(int $id)
    {
        $user = User::findOrFail($id);

        $stats = [
            'items_created' => \App\Models\Inventory::where('created_by', $id)->count(),
            'items_updated' => \App\Models\Inventory::where('last_updated_by', $id)->count(),
            'stock_logs'    => \App\Models\StockLog::where('created_by', $id)->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(int $id)
    {
        if ($id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
