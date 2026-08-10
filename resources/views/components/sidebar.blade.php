@auth
@php
$userType = strtolower(Auth::user()->user_type ?? '');
$menuItems = [
    'admin' => [
        ['label' => 'Menu', 'icon' => 'fa-solid fa-bars', 'route' => 'menu.index', 'roles' => ['*']],
        ['label' => 'Dashboard', 'icon' => 'fa-solid fa-chart-pie', 'route' => 'dashboard', 'roles' => ['*']],
        ['label' => 'Assignment & Issuance', 'icon' => 'fa-solid fa-hand-holding', 'route' => 'procurement.index', 'roles' => ['admin', 'Supply In-charge']],
        ['label' => 'Inventory', 'icon' => 'fa-solid fa-boxes-stacked', 'route' => 'inventory.index', 'roles' => ['admin', 'Supply In-charge']],
        ['label' => 'Property', 'icon' => 'fa-solid fa-couch', 'route' => 'property.index', 'roles' => ['admin', 'Property Custodian']],
        ['label' => 'Suppliers', 'icon' => 'fa-solid fa-truck', 'route' => 'suppliers.index', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
        ['label' => 'Procurement', 'icon' => 'fa-solid fa-file-invoice', 'route' => 'procurement.index', 'roles' => ['admin', 'Purchasing Officer', 'Purchasing Staff']],
        ['label' => 'Supply Requests', 'icon' => 'fa-solid fa-clipboard-list', 'route' => 'supply-requests.index', 'roles' => ['*']],
        ['label' => 'Canvass', 'icon' => 'fa-solid fa-scale-balanced', 'route' => 'canvass.index', 'roles' => ['admin', 'Purchasing Officer']],
        ['label' => 'Purchase Orders', 'icon' => 'fa-solid fa-file-signature', 'route' => 'purchase-orders.index', 'roles' => ['admin', 'Purchasing Officer']],
        ['label' => 'Notifications', 'icon' => 'fa-solid fa-bell', 'route' => 'notifications.index', 'roles' => ['*']],
        ['label' => 'Users', 'icon' => 'fa-solid fa-users', 'route' => 'users.index', 'roles' => ['admin', 'administrator']],
        ['label' => 'Reports', 'icon' => 'fa-solid fa-print', 'route' => 'reports.index', 'roles' => ['admin', 'Supply In-charge', 'Property Custodian']],
    ],
];
@endphp

<aside class="fixed inset-y-0 left-0 z-30 w-64 bg-emerald-950 text-white transform transition-all duration-200 ease-in-out lg:translate-x-0"
       :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full', sidebarCollapsed ? 'lg:w-16' : 'lg:w-64']">

    {{-- Logo --}}
    <div class="flex items-center justify-between h-16 px-4 border-b border-emerald-800" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
        <div class="flex items-center space-x-2" :class="sidebarCollapsed ? 'lg:hidden' : ''">
            <img src="{{ asset('favicon.png') }}" alt="DCC Logo" class="w-8 h-8 rounded-lg">
            <span class="font-semibold text-sm">DARTS</span>
        </div>
        <button @@click="sidebarCollapsed = !sidebarCollapsed; localStorage.setItem('sidebarCollapsed', sidebarCollapsed ? 'true' : 'false')"
                class="hidden lg:flex items-center text-emerald-300 hover:text-white transition" title="Toggle sidebar">
            <i class="fa-solid" :class="sidebarCollapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
        </button>
        <button @@click="sidebarOpen = false" class="lg:hidden text-emerald-300 hover:text-white">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @foreach($menuItems['admin'] as $item)
            @if(in_array('*', $item['roles']) || in_array($userType, array_map('strtolower', $item['roles'])))
                <a href="{{ route($item['route']) }}"
                   :title="sidebarCollapsed ? '{{ $item['label'] }}' : ''"
                   class="nav-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition-colors duration-150
                   {{ request()->routeIs($item['route'] . '*') ? 'bg-emerald-700 text-white' : 'text-emerald-200 hover:bg-emerald-800 hover:text-white' }}"
                   :class="sidebarCollapsed ? 'lg:justify-center' : ''">
                    <i class="nav-icon {{ $item['icon'] }} w-5 text-center" aria-hidden="true"></i>
                    <span class="nav-text" :class="sidebarCollapsed ? 'lg:hidden' : ''">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    {{-- User Info --}}
    <div class="border-t border-emerald-800 p-4">
        <div class="flex items-center space-x-3" :class="sidebarCollapsed ? 'lg:justify-center' : ''">
            <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-xs font-bold">
                {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'S', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0" :class="sidebarCollapsed ? 'lg:hidden' : ''">
                <p class="text-sm font-medium truncate">{{ Auth::user()->display_name }}</p>
                <p class="text-xs text-emerald-300 truncate">{{ Auth::user()->user_type }}</p>
            </div>
        </div>
    </div>
</aside>
@endauth
