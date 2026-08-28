@extends('layouts.app')

@section('title', 'My Profile - DARTS')
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Profile Overview Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="h-32 bg-gradient-to-r from-emerald-800 via-emerald-700 to-teal-700 relative">
            <div class="absolute inset-0 bg-white/5 backdrop-blur-[2px]"></div>
        </div>

        <div class="px-6 pb-6 pt-0 relative">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between -mt-16 sm:-mt-12 gap-4 pb-4 border-b border-gray-100">
                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 text-center sm:text-left">
                    <div class="h-24 w-24 rounded-2xl bg-emerald-600 border-4 border-white shadow-md flex items-center justify-center text-2xl font-bold text-white tracking-wider">
                        {{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? 'S', 0, 1) }}
                    </div>
                    <div class="pt-2">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2 justify-center sm:justify-start">
                            <span>{{ $user->display_name }}</span>
                            @if($user->academic_title)
                                <span class="text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2.5 py-0.5 rounded-full">
                                    {{ $user->academic_title }}
                                </span>
                            @endif
                        </h2>
                        <p class="text-sm text-gray-500 font-medium mt-0.5">
                            {{ $user->user_type }} &bull; {{ $user->department ?: 'Department Unassigned' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <a href="{{ route('profile.edit') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-user-pen text-xs"></i>
                        <span>Edit Profile</span>
                    </a>
                </div>
            </div>

            {{-- Details Grid --}}
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Personal Information --}}
                <div class="bg-slate-50/70 rounded-xl p-5 border border-slate-200/60">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-address-card text-emerald-600"></i>
                        Personal Information
                    </h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Title</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->title ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">First Name</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->first_name }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Middle Name</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->middle_name ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Last Name</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->last_name }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Suffix</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->suffix ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <dt class="text-gray-500 font-medium">Academic Title</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->academic_title ?: '—' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Account Details --}}
                <div class="bg-slate-50/70 rounded-xl p-5 border border-slate-200/60">
                    <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                        Account & Role Details
                    </h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Username</dt>
                            <dd class="text-gray-900 font-mono font-semibold">{{ $user->username }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Email</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->email ?: 'Not specified' }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Department</dt>
                            <dd class="text-gray-900 font-semibold">{{ $user->department ?: '—' }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5 border-b border-gray-200/50">
                            <dt class="text-gray-500 font-medium">Role / User Type</dt>
                            <dd class="text-emerald-700 font-semibold">{{ $user->user_type }}</dd>
                        </div>
                        <div class="flex justify-between py-1.5">
                            <dt class="text-gray-500 font-medium">Status</dt>
                            <dd>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ ($user->status ?? 'ACTIVE') === 'ACTIVE' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->status ?? 'ACTIVE' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
