@extends('layouts.app')

@section('title', 'Edit Profile - DARTS')
@section('page-title', 'Edit Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-200/80 flex items-center justify-between bg-slate-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Edit Profile Details</h2>
                <p class="text-xs text-gray-500 mt-0.5">Update your personal information and account credentials</p>
            </div>
            <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-emerald-700 transition">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back to Profile</span>
            </a>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Personal Information Section --}}
            <div>
                <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-user text-emerald-600"></i>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <select id="title" name="title"
                                class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <option value="">None</option>
                            @foreach(['Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Engr.'] as $t)
                                <option value="{{ $t }}" {{ old('title', $user->title) == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- First Name --}}
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('first_name') border-red-500 @enderror"
                               placeholder="First name">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Middle Name --}}
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="Middle name">
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('last_name') border-red-500 @enderror"
                               placeholder="Last name">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Suffix --}}
                    <div>
                        <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                        <select id="suffix" name="suffix"
                                class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <option value="">None</option>
                            @foreach(['Jr.', 'Sr.', 'II', 'III', 'IV'] as $s)
                                <option value="{{ $s }}" {{ old('suffix', $user->suffix) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Academic Title --}}
                    <div>
                        <label for="academic_title" class="block text-sm font-medium text-gray-700 mb-1">Academic Title</label>
                        <input type="text" id="academic_title" name="academic_title" value="{{ old('academic_title', $user->academic_title) }}"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="e.g. MBA, PhD, CPA">
                    </div>
                </div>
            </div>

            {{-- Account & Contact Section --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-envelope text-emerald-600"></i>
                    Account & Contact Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('username') border-red-500 @enderror"
                               placeholder="Login username">
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('email') border-red-500 @enderror"
                               placeholder="user@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department', $user->department) }}"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="e.g. MIS, Supply Room">
                    </div>

                    {{-- Role / User Type (Read-only) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">User Role</label>
                        <input type="text" value="{{ $user->user_type }}" disabled
                               class="w-full px-3.5 py-2 border border-gray-200 bg-gray-100 rounded-xl text-sm text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-400">User roles can only be changed by an administrator.</p>
                    </div>
                </div>
            </div>

            {{-- Change Password Section --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-emerald-600"></i>
                    Change Password
                </h3>
                <p class="text-xs text-gray-500 mb-4">Leave password fields blank if you do not wish to change your password.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('current_password') border-red-500 @enderror"
                               placeholder="Current password">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition @error('password') border-red-500 @enderror"
                               placeholder="Min 6 characters">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3.5 py-2 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                               placeholder="Confirm new password">
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex items-center justify-end gap-3 pt-5 border-t border-gray-200">
                <a href="{{ route('profile.show') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-sm transition">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
