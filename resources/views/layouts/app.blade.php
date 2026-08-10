<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'DARTS') - DCC Asset & Records Tracking</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu-modern.css') }}">
</head>
<body class="h-full antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true', darkMode: false }">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen" @@click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden" style="display: none;"></div>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col"
             :class="sidebarCollapsed ? 'lg:pl-16' : 'lg:pl-64'">
            {{-- Navbar --}}
            @include('components.navbar')

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg shadow-sm" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg shadow-sm" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-200 bg-white px-6 py-3 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} DCC Asset & Records Tracking System. All rights reserved.
            </footer>
        </div>
    </div>

    {{-- Toast Container --}}
    <div x-data="toastHandler()" @@notify.window="add($event.detail)" class="fixed bottom-4 right-4 z-50 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @@click="remove(toast.id)"
                 class="cursor-pointer px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium"
                 :class="{ 'bg-green-600': toast.type === 'success', 'bg-red-600': toast.type === 'error', 'bg-blue-600': toast.type === 'info' }"
                 x-text="toast.message">
            </div>
        </template>
    </div>

    {{-- Global View Modal --}}
    <div x-data="viewModal()"
         x-init="init()"
         x-effect="document.body.style.overflow = open ? 'hidden' : ''">
        <template x-teleport="body">
            <div x-show="open" x-cloak
                 @@keydown.escape.window="close()"
                 aria-modal="true" role="dialog"
                 class="fixed inset-0 z-[60]">
                <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                    {{-- Backdrop --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         @@click="close()"
                         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

                    {{-- Panel --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                         class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl border border-gray-200/80 overflow-hidden">
                        {{-- Header --}}
                        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-white">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center space-x-2 min-w-0 flex-1">
                                <i class="fa-solid fa-eye text-emerald-600 shrink-0"></i>
                                <span x-text="title" class="truncate"></span>
                            </h3>
                            <button type="button" @@click="close()" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition" title="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        {{-- Body --}}
                        <div class="max-h-[75vh] overflow-y-auto">
                            <div x-show="loading" class="flex items-center justify-center py-16">
                                <i class="fa-solid fa-circle-notch fa-spin text-emerald-600 text-2xl"></i>
                            </div>
                            <div x-ref="body" class="modal-view-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        /**
         * Opens the global view modal from a plain (non-Alpine) element.
         * Reads data-url / data-title attributes and forwards a CustomEvent.
         * Returns false so onclick handlers can prevent default navigation.
         */
        function openViewModal(elm) {
            window.dispatchEvent(new CustomEvent('open-view-modal', {
                detail: { url: elm.dataset.url, title: elm.dataset.title }
            }));
            return false;
        }

        function viewModal() {
            return {
                open: false,
                loading: false,
                title: 'Details',
                init() {
                    window.addEventListener('open-view-modal', (e) => {
                        this.openModal(e.detail || {});
                    });
                },
                openModal({ url, title }) {
                    if (!url) return;
                    this.title = title || 'Details';
                    this.open = true;
                    this.loading = true;
                    this.$refs.body.innerHTML = '';
                    const sep = url.includes('?') ? '&' : '?';
                    fetch(url + sep + 'modal=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((r) => {
                            if (!r.ok) throw new Error('Request failed');
                            return r.text();
                        })
                        .then((html) => { this.$refs.body.innerHTML = html; })
                        .catch(() => {
                            this.$refs.body.innerHTML = '<div class="px-6 py-12 text-center text-gray-500"><i class="fa-solid fa-triangle-exclamation text-3xl text-red-400 mb-3"></i><p class="text-sm">Failed to load details.</p></div>';
                        })
                        .finally(() => { this.loading = false; });
                },
                close() {
                    this.open = false;
                    this.$refs.body.innerHTML = '';
                    this.title = 'Details';
                }
            };
        }

        function toastHandler() {
            return {
                toasts: [],
                add(detail) {
                    const id = Date.now();
                    this.toasts.push({ id, message: detail.message, type: detail.type || 'info', show: true });
                    setTimeout(() => { this.remove(id); }, 4000);
                },
                remove(id) {
                    const toast = this.toasts.find(t => t.id === id);
                    if (toast) toast.show = false;
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 300);
                }
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
