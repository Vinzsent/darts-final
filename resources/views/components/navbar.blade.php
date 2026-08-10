@auth
<header class="sticky top-0 z-10 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
        {{-- Left: Hamburger + Page Title --}}
        <div class="flex items-center space-x-3">
            <button @@click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 hidden sm:block">@yield('page-title', 'Dashboard')</h1>
        </div>

        {{-- Right: Notifications + Profile --}}
        <div class="flex items-center space-x-4">
            {{-- Notifications --}}
            <a href="{{ route('notifications.index') }}" class="relative text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-bell text-lg"></i>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">0</span>
            </a>

            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @@click="open = !open" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900">
                    <div class="w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-xs font-bold">
                        {{ substr(Auth::user()->first_name ?? 'U', 0, 1) }}{{ substr(Auth::user()->last_name ?? 'S', 0, 1) }}
                    </div>
                    <span class="hidden sm:block">{{ Auth::user()->display_name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>

                <div x-show="open" @@click.outside="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                     style="display: none;">
                    <div class="px-4 py-2 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900">{{ Auth::user()->display_name }}</p>
                        <p class="text-xs text-gray-500">{{ Auth::user()->user_type }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                            <i class="fa-solid fa-sign-out-alt mr-2"></i> Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
@endauth
