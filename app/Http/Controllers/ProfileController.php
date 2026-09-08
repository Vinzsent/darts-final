<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Handle profile photo removal
        if ($request->boolean('remove_profile')) {
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }
            $data['profile'] = null;
        }

        // Handle new profile photo upload
        if ($request->hasFile('profile')) {
            if ($user->profile && Storage::disk('public')->exists($user->profile)) {
                Storage::disk('public')->delete($user->profile);
            }
            $data['profile'] = $request->file('profile')->store('profiles', 'public');
        }

        unset($data['remove_profile']);

        if (!empty($data['password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return back()
                    ->withErrors(['current_password' => 'The current password you provided does not match our records.'])
                    ->withInput();
            }
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['current_password'], $data['password_confirmation']);

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
