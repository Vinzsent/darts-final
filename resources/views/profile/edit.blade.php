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
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6"
              x-data="{
                  previewUrl: '{{ $user->profile_url }}',
                  removePhoto: false,
                  handleFileSelect(event) {
                      const file = event.target.files[0];
                      if (file) {
                          if (file.size > 2 * 1024 * 1024) {
                              alert('File size exceeds 2MB limit.');
                              event.target.value = '';
                              return;
                          }
                          this.removePhoto = false;
                          this.previewUrl = URL.createObjectURL(file);
                      }
                  },
                  clearPhoto() {
                      this.previewUrl = null;
                      this.removePhoto = true;
                      $refs.fileInput.value = '';
                  }
              }">
            @csrf
            @method('PUT')

            {{-- Hidden input for photo removal --}}
            <input type="hidden" name="remove_profile" :value="removePhoto ? '1' : '0'">

            {{-- Profile Photo Upload Section --}}
            <div class="p-5 bg-gradient-to-r from-emerald-50/70 via-teal-50/40 to-slate-50/80 rounded-2xl border border-emerald-100/80">
                <h3 class="text-sm font-semibold text-emerald-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-camera text-emerald-600"></i>
                    Profile Picture
                </h3>

                <div class="flex flex-col sm:flex-row items-center gap-5">
                    {{-- Avatar Preview Frame --}}
                    <div class="relative group shrink-0">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden shadow-md border-2 border-white ring-2 ring-emerald-500/20 bg-emerald-700 flex items-center justify-center text-white font-bold text-2xl tracking-wider">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" alt="Profile Preview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <span>{{ $user->initials }}</span>
                            </template>
                        </div>
                        <button type="button" @click="$refs.fileInput.click()"
                                class="absolute inset-0 bg-black/40 text-white rounded-2xl flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                            <i class="fa-solid fa-camera text-base"></i>
                            <span class="text-[10px] font-medium mt-0.5">Change</span>
                        </button>
                    </div>

                    {{-- Upload Controls & Guidelines --}}
                    <div class="flex-1 text-center sm:text-left space-y-2">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                            <input type="file" id="profile" name="profile" x-ref="fileInput" @change="handleFileSelect"
                                   accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden">
                            <button type="button" @click="$refs.fileInput.click()"
                                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-xl border border-emerald-300 shadow-sm transition">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Upload New Photo</span>
                            </button>
                            <button type="button" x-show="previewUrl" @click="clearPhoto()"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold rounded-xl border border-red-200 transition"
                                    style="display: none;">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                                <span>Remove</span>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500">
                            Allowed formats: <span class="font-medium text-gray-700">JPG, PNG, WEBP</span> &bull; Max size: <span class="font-medium text-gray-700">2MB</span>
                        </p>
                        @error('profile')
                            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

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
