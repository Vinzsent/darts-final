@extends('layouts.app')

@section('title', 'Create User - DARTS')
@section('page-title', 'Create User')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">New User</h2>
                <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
                </a>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Personal Information --}}
            <div>
                <h3 class="text-base font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <select id="title" name="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">None</option>
                            @foreach(['Mr.', 'Ms.', 'Mrs.', 'Dr.', 'Engr.'] as $t)
                                <option value="{{ $t }}" {{ old('title') == $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- First Name --}}
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('first_name') border-red-500 @enderror"
                               placeholder="First name">
                        @error('first_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Middle Name --}}
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Middle name">
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('last_name') border-red-500 @enderror"
                               placeholder="Last name">
                        @error('last_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Suffix --}}
                    <div>
                        <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">Suffix</label>
                        <select id="suffix" name="suffix"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">None</option>
                            @foreach(['Jr.', 'Sr.', 'II', 'III', 'IV'] as $s)
                                <option value="{{ $s }}" {{ old('suffix') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Academic Title --}}
                    <div>
                        <label for="academic_title" class="block text-sm font-medium text-gray-700 mb-1">Academic Title</label>
                        <input type="text" id="academic_title" name="academic_title" value="{{ old('academic_title') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="e.g. MBA, PhD, CPA">
                    </div>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-medium text-gray-900 mb-4">Account Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('username') border-red-500 @enderror"
                               placeholder="Login username">
                        @error('username')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('password') border-red-500 @enderror"
                               placeholder="Minimum 6 characters">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email') border-red-500 @enderror"
                               placeholder="user@example.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- User Type --}}
                    <div>
                        <label for="user_type" class="block text-sm font-medium text-gray-700 mb-1">
                            User Type <span class="text-red-500">*</span>
                        </label>
                        <select id="user_type" name="user_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('user_type') border-red-500 @enderror">
                            @foreach(\App\Http\Controllers\UsersController::userTypes() as $type)
                                <option value="{{ $type }}" {{ old('user_type', 'Admin') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('user_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <input type="text" id="department" name="department" value="{{ old('department') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="e.g. MIS, Supply Room">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="ACTIVE" {{ old('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="INACTIVE" {{ old('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-save mr-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
