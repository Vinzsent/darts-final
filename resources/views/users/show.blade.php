@extends(request()->has('modal') ? 'layouts.blank' : 'layouts.app')

@section('title', $user->display_name . ' - User - DARTS')
@section('page-title', $user->display_name)

@section('content')
<div class="space-y-6">
    {{-- Back link --}}
    @if(!request()->has('modal'))
    <a href="{{ route('users.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Users
    </a>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Personal Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-user text-emerald-600"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                    </div>
                    @if(!request()->has('modal'))
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1.5 text-sm bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ trim(implode(' ', array_filter([$user->title, $user->first_name, $user->middle_name, $user->last_name]))) }}
                                {{ $user->suffix ? ', ' . $user->suffix : '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Title</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->academic_title ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->department ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">User Type</dt>
                            <dd class="mt-1">
                                @php
                                $badgeType = match(strtolower($user->user_type ?? '')) {
                                    'admin', 'administrator' => 'danger',
                                    'supply in-charge' => 'info',
                                    'property custodian' => 'indigo',
                                    'purchasing officer' => 'success',
                                    'purchasing staff' => 'warning',
                                    default => 'default',
                                };
                                @endphp
                                <x-badge :type="$badgeType">{{ $user->user_type ?? 'N/A' }}</x-badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                @if($user->status)
                                    <x-badge :type="strtoupper($user->status) === 'ACTIVE' ? 'success' : 'danger'">{{ $user->status }}</x-badge>
                                @else
                                    <span class="text-xs text-gray-400">--</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Account Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-key text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Account Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Username</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $user->username }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Password</dt>
                            <dd class="mt-1 text-sm text-gray-400">••••••••</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Account Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at ? date('M d, Y h:i A', strtotime($user->created_at)) : '--' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Profile Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-2xl font-bold mb-3">
                        {{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? 'S', 0, 1) }}
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">{{ $user->display_name }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $user->user_type ?? 'N/A' }}</p>
                    @if($user->status)
                        <div class="mt-3">
                            <x-badge :type="strtoupper($user->status) === 'ACTIVE' ? 'success' : 'danger'">{{ $user->status }}</x-badge>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Activity Stats --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Activity</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Items Created</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($stats['items_created']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Items Updated</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($stats['items_updated']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Stock Adjustments</span>
                        <span class="text-sm font-semibold text-gray-900">{{ number_format($stats['stock_logs']) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
