@extends('layouts.app')

@section('title', 'DARTS Menu')
@section('page-title', 'DARTS Menu')

@section('content')
<div class="min-h-[calc(100vh-220px)] bg-gradient-to-br from-slate-50 to-emerald-50 px-2 py-8">
    <div class="mx-auto max-w-7xl">
        <div class="text-center mb-8">
            <div class="text-center mb-8">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur rounded-2xl mb-4">
        <img
            src="/DCC2.png"
            alt="Logo"
            class="w-44 h-44 object-contain"
        >
    </div>
</div>
            <h1 class="mt-5 text-4xl font-bold text-slate-900 tracking-tight">Welcome to DARTS</h1>
            <p class="mt-2 text-sm text-slate-500">Manage your assets efficiently with our comprehensive tools</p>
        </div>

        @php
            $userType = strtolower(Auth::user()->user_type ?? '');
            $isAdmin = in_array($userType, ['admin', 'administrator'], true);

            $allMenus = [
                ['label' => 'Supply Requisition', 'icon' => 'fa-clipboard-list', 'route' => 'supply-requests.index', 'color' => 'emerald', 'description' => 'Manage requests and approvals', 'roles' => ['*']],
                ['label' => 'Property Requisition', 'icon' => 'fa-house-circle-check', 'route' => 'property.index', 'color' => 'cyan', 'description' => 'Property request and allocation flow', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'School Year', 'icon' => 'fa-calendar-days', 'route' => 'reports.index', 'color' => 'blue', 'description' => 'Academic cycle records', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Suppliers', 'icon' => 'fa-warehouse', 'route' => 'suppliers.index', 'color' => 'violet', 'description' => 'Manage suppliers and vendors', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
                ['label' => 'Service Form', 'icon' => 'fa-file-lines', 'route' => 'reports.index', 'color' => 'blue', 'description' => 'Service and request documentation', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Service Form Reports', 'icon' => 'fa-chart-column', 'route' => 'reports.index', 'color' => 'orange', 'description' => 'Reporting and analytics', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Printing Header Settings', 'icon' => 'fa-print', 'route' => 'reports.index', 'color' => 'indigo', 'description' => 'Configure report documents', 'roles' => ['admin']],
                ['label' => 'Assignment & Issuance', 'icon' => 'fa-hand-holding', 'route' => 'procurement.index', 'color' => 'amber', 'description' => 'Issue and assign inventory items', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Budget Overview', 'icon' => 'fa-wallet', 'route' => 'reports.index', 'color' => 'slate', 'description' => 'Budget allocations and monitoring', 'roles' => ['admin']],
                ['label' => 'Procurement', 'icon' => 'fa-file-invoice', 'route' => 'procurement.index', 'color' => 'yellow', 'description' => 'Procurement workflow and purchasing', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
                ['label' => 'Received Items', 'icon' => 'fa-truck-ramp-box', 'route' => 'procurement.index', 'color' => 'green', 'description' => 'Received goods and documentation', 'roles' => ['admin', 'Purchasing Officer']],
                ['label' => 'Supply Inventory Management', 'icon' => 'fa-boxes-stacked', 'route' => 'inventory.index', 'color' => 'emerald', 'description' => 'Track stock, stockouts, and reorder levels', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Personnel', 'icon' => 'fa-users', 'route' => 'personnel.index', 'color' => 'emerald', 'description' => 'Employee directory and asset assignments', 'roles' => ['admin', 'Property Custodian', 'Supply In-charge']],

                ['label' => 'Property Inventory', 'icon' => 'fa-landmark', 'route' => 'property.index', 'color' => 'indigo', 'description' => 'Asset and property records', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'Asset Registration', 'icon' => 'fa-tags', 'route' => 'property.index', 'color' => 'rose', 'description' => 'Register property and asset master data', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'Maintenance', 'icon' => 'fa-screwdriver-wrench', 'route' => 'property.index', 'color' => 'cyan', 'description' => 'Maintenance and repair tracking', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'Disposal', 'icon' => 'fa-trash-can', 'route' => 'property.index', 'color' => 'red', 'description' => 'Disposition and disposal register', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'Property Reports', 'icon' => 'fa-chart-pie', 'route' => 'reports.index', 'color' => 'blue', 'description' => 'Property report summaries', 'roles' => ['admin', 'Property Custodian']],
                ['label' => 'Supply Reports', 'icon' => 'fa-file-export', 'route' => 'reports.index', 'color' => 'violet', 'description' => 'Supply operations statistics', 'roles' => ['admin', 'Supply In-charge']],
                ['label' => 'Notifications', 'icon' => 'fa-bell', 'route' => 'notifications.index', 'color' => 'sky', 'description' => 'Notifications and activity feed', 'roles' => ['*']],
                ['label' => 'My Profile', 'icon' => 'fa-id-card', 'route' => 'profile.show', 'color' => 'teal', 'description' => 'View and edit account information', 'roles' => ['*']],
                ['label' => 'System Settings', 'icon' => 'fa-gear', 'route' => 'settings', 'color' => 'gray', 'description' => 'Configuration and system controls', 'roles' => ['admin', 'administrator']],
            ];

            $visibleMenus = [];
            foreach ($allMenus as $menu) {
                $menuRoles = $menu['roles'];
                if (in_array('*', $menuRoles, true) || $isAdmin || in_array($userType, array_map('strtolower', $menuRoles), true)) {
                    $visibleMenus[] = $menu;
                }
            }
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($visibleMenus as $menu)
                @php
                    $route = $menu['route'];
                    $btnLabel = $route === 'settings' ? 'Open' : 'Access';
                    $iconSize = 'text-2xl';
                @endphp

                @if($menu['label'] === 'System Settings')
                    <div class="menu-card group relative overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <a href="javascript:void(0)" data-modal-trigger="settings" class="block p-6 h-full">
                            <div class="flex items-center justify-between">
                                <span class="menu-card-icon inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-sm">
                                    <i class="fa-solid {{ $menu['icon'] }} {{ $iconSize }}"></i>
                                </span>
                                <span class="rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wide bg-slate-100 text-slate-700">Secure</span>
                            </div>
                            <div class="mt-5">
                                <h3 class="menu-card-title text-sm font-bold text-slate-900">{{ $menu['label'] }}</h3>
                                <p class="menu-card-copy mt-2 text-xs leading-5 text-slate-500">{{ $menu['description'] }}</p>
                                <div class="mt-5">
                                    <span class="menu-access-button inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-xs font-semibold text-white shadow-sm transition group-hover:bg-slate-700">
                                        <i class="fa-solid fa-right-to-bracket"></i>{{ $btnLabel }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @else
                    <div class="menu-card group relative overflow-hidden rounded-2xl border border-slate-200 bg-white/90 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <a href="{{ route($route) }}" class="block p-6 h-full">
                            <div class="flex items-center justify-between">
                                <span class="menu-card-icon inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-900 text-white shadow-sm">
                                    <i class="fa-solid {{ $menu['icon'] }} {{ $iconSize }}"></i>
                                </span>
                                <span class="menu-open-badge rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700">Open</span>
                            </div>
                            <div class="mt-5">
                                <h3 class="menu-card-title text-sm font-bold text-slate-900">{{ $menu['label'] }}</h3>
                                <p class="menu-card-copy mt-2 text-xs leading-5 text-slate-500">{{ $menu['description'] }}</p>
                                <div class="mt-5">
                                    <span class="menu-access-button inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2 text-xs font-semibold text-white shadow-sm transition group-hover:from-emerald-700 group-hover:to-emerald-800">
                                        <i class="fa-solid fa-right-to-bracket"></i>{{ $btnLabel }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div id="settings-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/60 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-3xl border border-white/20 bg-white p-6 shadow-2xl">
        <div class="flex items-center justify-between">
            <div>
                <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="mt-4 text-xl font-bold text-slate-900">System Settings</h2>
            </div>
            <button type="button" data-modal-close="settings" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-900">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p class="mt-4 text-sm text-slate-500">Enter the administrator password to continue.</p>

        <form method="POST" action="{{ route('menu.settings.unlock') }}" class="mt-5 space-y-4">
            @csrf
            <label class="block">
                <span class="mb-2 block text-xs font-bold uppercase tracking-wide text-slate-500">Password</span>
                <input type="password" name="password" required autocomplete="current-password" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-300" placeholder="Enter password">
            </label>
            @if(session('settings_error'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-medium text-red-700">
                    {{ session('settings_error') }}
                </div>
            @endif
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" data-modal-close="settings" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    <i class="fa-solid fa-unlock mr-2"></i>Unlock
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('settings-modal');
        const openers = document.querySelectorAll('[data-modal-trigger="settings"]');
        const closers = document.querySelectorAll('[data-modal-close="settings"]');

        openers.forEach(function (el) {
            el.addEventListener('click', function () {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(function () {
                    const input = modal.querySelector('input[name="password"]');
                    if (input) input.focus();
                }, 20);
            });
        });

        closers.forEach(function (el) {
            el.addEventListener('click', function () {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
</script>
@endpush
@endsection
