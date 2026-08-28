<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $userType = strtolower($user?->user_type ?? '');
        $isAdmin = in_array($userType, ['admin', 'administrator'], true);

        return view('menu.index', compact('isAdmin', 'userType'));
    }

    public function settingsUnlock(Request $request)
    {
        $password = trim((string) $request->input('password'));

        if ($password !== 'misadmin') {
            return redirect()->route('menu.index')
                ->with('settings_error', 'Invalid password. Access denied.');
        }

        return redirect()->route('users.index')
            ->with('success', 'System settings unlocked. Manage users below.');
    }
}
