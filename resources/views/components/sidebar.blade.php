@auth
@php
$userType = strtolower(Auth::user()->user_type ?? '');
$menuItems = [
    'admin' => [
        ['label' => 'Menu', 'icon' => 'fa-solid fa-bars', 'route' => 'menu.index', 'roles' => ['*']],
        ['label' => 'Dashboard', 'icon' => 'fa-solid fa-chart-pie', 'route' => 'dashboard', 'roles' => ['*']],
        ['label' => 'Assignment & Issuance', 'icon' => 'fa-solid fa-hand-holding', 'route' => 'assignment-issuance.index', 'roles' => ['admin', 'Supply In-charge']],
        ['label' => 'Inventory', 'icon' => 'fa-solid fa-boxes-stacked', 'route' => 'inventory.index', 'roles' => ['admin', 'Supply In-charge']],
        ['label' => 'Property', 'icon' => 'fa-solid fa-couch', 'route' => 'property.index', 'roles' => ['admin', 'Property Custodian']],
        ['label' => 'Suppliers', 'icon' => 'fa-solid fa-truck', 'route' => 'suppliers.index', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
        ['label' => 'Procurement', 'icon' => 'fa-solid fa-file-invoice', 'route' => 'procurement.index', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
        ['label' => 'Supply Requests', 'icon' => 'fa-solid fa-clipboard-list', 'route' => 'supply-requests.index', 'roles' => ['*']],
        ['label' => 'Canvass', 'icon' => 'fa-solid fa-scale-balanced', 'route' => 'canvass.index', 'roles' => ['admin', 'Purchasing Officer']],
        ['label' => 'Purchase Orders', 'icon' => 'fa-solid fa-file-signature', 'route' => 'purchase-orders.index', 'roles' => ['admin', 'Purchasing Officer']],
        ['label' => 'Notifications', 'icon' => 'fa-solid fa-bell', 'route' => 'notifications.index', 'roles' => ['*']],
        ['label' => 'Users', 'icon' => 'fa-solid fa-user-shield', 'route' => 'users.index', 'roles' => ['admin', 'administrator']],
        ['label' => 'Personnel', 'icon' => 'fa-solid fa-users', 'route' => 'personnel.index', 'roles' => ['admin', 'Property Custodian', 'Supply In-charge']],
        ['label' => 'Reports', 'icon' => 'fa-solid fa-print', 'route' => 'reports.index', 'roles' => ['admin', 'Supply In-charge', 'Property Custodian']],
        ['label' => 'Profile', 'icon' => 'fa-solid fa-id-card', 'route' => 'profile.show', 'roles' => ['*']],
    ],
];
@endphp

<!-- Mobile Backdrop -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
     x-cloak></div>

<!-- Sidebar Aside -->
<aside class="fixed inset-y-0 left-0 z-50 flex h-screen flex-col border-r border-emerald-800/80 bg-emerald-950 text-white transition-all duration-300 ease-in-out lg:sticky lg:top-0 lg:z-auto lg:shrink-0"
       :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0', sidebarCollapsed ? 'lg:w-20 w-64' : 'w-64']"
       @keydown.escape.window="sidebarOpen = false">
    
    <!-- Sidebar Header -->
    <div class="flex h-16 shrink-0 items-center justify-between border-b border-emerald-800/80 px-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden" :class="sidebarCollapsed ? 'lg:justify-center lg:w-full' : ''">
            <img src="{{ asset('favicon.png') }}" alt="DCC Logo" class="h-8 w-8 rounded-lg object-cover shrink-0">
            <span x-show="!sidebarCollapsed" class="text-base font-bold tracking-wide text-white truncate">DARTS</span>
        </a>

        <!-- Desktop Collapse Button -->
        <button type="button"
                @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:flex items-center justify-center h-8 w-8 rounded-lg border border-emerald-700/60 bg-emerald-900/60 text-emerald-200 transition hover:bg-emerald-800 hover:text-white"
                :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'">
            <i class="fa-solid text-xs" :class="sidebarCollapsed ? 'fa-angle-right' : 'fa-angle-left'"></i>
        </button>

        <!-- Mobile Close Button -->
        <button type="button"
                @click="sidebarOpen = false"
                class="flex lg:hidden items-center justify-center h-8 w-8 rounded-lg text-emerald-300 hover:bg-emerald-800 hover:text-white transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        @foreach($menuItems['admin'] as $item)
            @if(in_array('*', $item['roles']) || in_array($userType, array_map('strtolower', $item['roles'])))
                @php
                    $isActive = request()->routeIs($item['route']) || (request()->routeIs($item['route'] . '.*') && $item['route'] !== 'menu.index');
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors duration-150 {{ $isActive ? 'bg-emerald-600 text-white shadow-sm' : 'text-emerald-100/90 hover:bg-emerald-900/70 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'lg:justify-center lg:px-2' : ''"
                   :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''">
                    <i class="{{ $item['icon'] }} w-5 text-center shrink-0 text-base"></i>
                    <span x-show="!sidebarCollapsed" class="truncate">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <!-- User Profile Footer -->
    <div class="border-t border-emerald-800/80 p-3 shrink-0 bg-emerald-950/80">
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-emerald-900/70 group"
           :class="sidebarCollapsed ? 'lg:justify-center' : ''"
           :title="sidebarCollapsed ? '{{ Auth::user()->display_name }} (Profile)' : ''">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white shadow-sm ring-2 ring-emerald-500/30 group-hover:ring-emerald-400">
                {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'S', 0, 1) }}
            </div>
            <div x-show="!sidebarCollapsed" class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-white group-hover:text-emerald-200 transition-colors">{{ Auth::user()->display_name }}</p>
                <p class="truncate text-xs text-emerald-300/80">{{ Auth::user()->user_type }}</p>
            </div>
            <div x-show="!sidebarCollapsed" class="text-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </div>
        </a>
    </div>
</aside>
@endauth
