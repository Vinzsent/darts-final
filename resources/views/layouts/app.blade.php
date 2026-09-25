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
    <style>
        /* Mobile card-list tables (Rules.md: List/Card view on mobile) */
        @media (max-width: 767px) {
            table.table-cards thead { display: none; }
            table.table-cards tbody { display: block; }
            table.table-cards tr {
                display: block; background: #fff;
                border: 1px solid #e5e7eb; border-radius: 0.75rem;
                box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
                padding: 0.5rem; margin-bottom: 0.75rem;
            }
            table.table-cards td {
                display: flex; align-items: center; justify-content: space-between;
                gap: 1rem; width: 100%; padding: 0.5rem 0.5rem;
                border: 0 !important; text-align: right; white-space: normal;
            }
            table.table-cards td::before {
                content: attr(data-label);
                font-size: 0.7rem; font-weight: 600; color: #6b7280;
                text-transform: uppercase; letter-spacing: 0.03em; text-align: left;
                flex-shrink: 0;
            }
            table.table-cards td[data-label=""]::before { content: none; }
            table.table-cards td[colspan] { justify-content: center; text-align: center; padding: 1rem 0.5rem; }
            table.table-cards td[colspan]::before { content: none; }
            table.table-cards tr:hover { background: #fff; }
        }
        @keyframes fadeScaleIn {
            from { opacity: 0; transform: scale(0.92) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .cd-modal-panel { animation: fadeScaleIn 0.2s ease-out both; }
    </style>
</head>
<body class="h-full antialiased bg-slate-100" x-data="{
        darkMode: false,
        sidebarOpen: false,
        sidebarCollapsed: false
    }">
    @if(!request()->routeIs('menu.index'))
        <div class="min-h-screen flex">
            @include('components.sidebar')

            <div class="flex min-w-0 flex-1 flex-col transition-all duration-200">
                @include('components.navbar')

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

                <footer class="border-t border-gray-200 bg-white px-6 py-3 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} DCC Asset & Records Tracking System. All rights reserved.
                </footer>
            </div>
        </div>
    @else
        <div class="min-h-screen flex flex-col">
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
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

            <footer class="border-t border-gray-200 bg-white px-6 py-3 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} DCC Asset & Records Tracking System. All rights reserved.
            </footer>
        </div>
    @endif

    {{-- Toast Container --}}
    <div x-data="toastHandler()" @@notify.window="add($event.detail)" class="fixed bottom-4 right-4 z-50 space-y-2">
        @if(session('success'))
            <div x-init="$nextTick(() => add({ message: @js(session('success')), type: 'success' }))"></div>
        @endif
        @if(session('error'))
            <div x-init="$nextTick(() => add({ message: @js(session('error')), type: 'error' }))"></div>
        @endif
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @@click="remove(toast.id)"
                 class="cursor-pointer px-4 py-3 rounded-lg shadow-lg text-white text-sm font-medium flex items-center gap-2"
                 :class="{ 'bg-emerald-600': toast.type === 'success', 'bg-red-600': toast.type === 'error', 'bg-blue-600': toast.type === 'info' }">
                <i class="fa-solid" :class="{ 'fa-circle-check': toast.type === 'success', 'fa-circle-exclamation': toast.type === 'error', 'fa-circle-info': toast.type === 'info' }"></i>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    {{-- Global View Modal --}}
    <div x-data="viewModal()"
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
/**
         * Re-executes <script> tags inside HTML injected via innerHTML
         * (browsers do not run them automatically). External scripts
         * already loaded are skipped, inline scripts always re-run.
         */
        function executeModalScripts(root) {
            if (!root) return;
            if (window.enhanceTables) window.enhanceTables(root);
            const scripts = Array.from(root.querySelectorAll('script'));
            let chain = Promise.resolve();
            scripts.forEach((old) => {
                if (old.src) {
                    if (document.querySelector('script[src="' + old.src + '"]')) { old.remove(); return; }
                    chain = chain.then(() => new Promise((res) => {
                        const s = document.createElement('script');
                        s.src = old.src;
                        s.onload = () => res();
                        s.onerror = () => res();
                        document.body.appendChild(s);
                        old.remove();
                    }));
                } else {
                    const code = old.textContent;
                    chain = chain.then(() => {
                        const s = document.createElement('script');
                        s.textContent = code;
                        document.body.appendChild(s);
                        old.remove();
                    });
                }
            });
        }

        /**
         * Mobile card-view: converts data tables into stacked cards on small
         * screens. Reads <th> labels into td[data-label]; CSS does the rest.
         * Opt out per table with data-table="plain".
         */
        window.enhanceTables = function (root) {
            const scope = root || document;
            scope.querySelectorAll('table').forEach((table) => {
                if (table.dataset.table === 'plain' || table.classList.contains('table-cards')) return;
                const head = table.querySelector('thead');
                if (!head || !table.querySelector('tbody')) return;
                const labels = Array.from(head.querySelectorAll('th')).map((th) => th.textContent.trim());
                table.classList.add('table-cards');
                table.querySelectorAll('tbody tr').forEach((tr) => {
                    tr.querySelectorAll('td').forEach((td, i) => {
                        td.dataset.label = labels[i] || '';
                    });
                });
            });
        };
        document.addEventListener('DOMContentLoaded', () => window.enhanceTables());

        /**
         * AJAX pagination for regular (non-modal) pages: swaps only the
         * table container, keeping scroll position — no jump to top.
         */
        function cssPath(el) {
            const parts = [];
            while (el && el !== document.body) {
                let i = 1, sib = el;
                while ((sib = sib.previousElementSibling)) { if (sib.tagName === el.tagName) i++; }
                parts.unshift(el.tagName.toLowerCase() + ':nth-of-type(' + i + ')');
                el = el.parentElement;
            }
            return 'body > ' + parts.join(' > ');
        }
        document.addEventListener('click', (e) => {
            const link = e.target.closest('nav[aria-label="Pagination Navigation"] a');
            if (!link || link.closest('.modal-view-body')) return;
            e.preventDefault();

            // Find the smallest ancestor holding both the table and pagination
            let container = link.closest('nav[aria-label="Pagination Navigation"]').parentElement;
            while (container && container !== document.body && !(container.querySelector('table') && container.contains(link))) {
                container = container.parentElement;
            }
            if (!container || container === document.body) { window.location.href = link.href; return; }

            const path = cssPath(container);
            container.style.opacity = '0.5';

            fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then((r) => r.text())
                .then((html) => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const next = doc.querySelector(path);
                    if (!next) { window.location.href = link.href; return; }
                    container.replaceWith(next);
                    window.enhanceTables(next);
                    history.pushState({}, '', link.href);
                })
                .catch(() => { window.location.href = link.href; })
                .finally(() => {
                    const el = document.querySelector(path);
                    if (el) el.style.opacity = '';
                });
        });

        function viewModal() {
            return {
                open: false,
                loading: false,
                title: 'Details',
                currentUrl: '',
                _initialized: false,
                init() {
                    if (this._initialized || window._viewModalInitialized) return;
                    this._initialized = true;
                    window._viewModalInitialized = true;

                    window.addEventListener('open-view-modal', (e) => {
                        this.openModal(e.detail || {});
                    });

                    // Handle form submissions inside modal via AJAX
                    document.addEventListener('submit', (e) => {
                        if (!this.open) return;
                        const form = e.target;
                        if (!this.$refs.body || !this.$refs.body.contains(form)) return;
                        if (form.dataset.modalSubmitting === '1') {
                            e.preventDefault();
                            e.stopPropagation();
                            return;
                        }
                        form.dataset.modalSubmitting = '1';
                        e.preventDefault();
                        e.stopPropagation();

                        const btn = form.querySelector('button[type="submit"]');
                        const origHtml = btn ? btn.innerHTML : '';
                        if (btn) {
                            btn.disabled = true;
                            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Saving...';
                        }

                        const formData = new FormData(form);
                        const actionUrl = form.action || window.location.href;

                        fetch(actionUrl, {
                            method: form.method || 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json, text/html'
                            }
                        })
                        .then(async (response) => {
                            if (!response.ok) {
                                const errData = await response.json().catch(() => null);
                                const errMsg = errData?.message || (errData?.errors ? Object.values(errData.errors).flat().join(' ') : 'Adjustment failed.');
                                throw new Error(errMsg);
                            }
                            return response.json().catch(() => ({ success: true }));
                        })
                        .then((data) => {
                            const msg = data.message || 'Saved successfully.';
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: msg, type: 'success' } }));

                            // Refresh modal content to reflect changes (new stock and movements)
                            if (this.currentUrl) {
                                const sep = this.currentUrl.includes('?') ? '&' : '?';
                                fetch(this.currentUrl + sep + 'modal=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                                    .then(r => r.text())
                                    .then(html => {
                                        this.$refs.body.innerHTML = html;
                                        executeModalScripts(this.$refs.body);
                                    });
                            }
                        })
                        .catch((err) => {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: err.message || 'An error occurred.', type: 'error' } }));
                        })
                        .finally(() => {
                            form.dataset.modalSubmitting = '';
                            if (btn) {
                                btn.disabled = false;
                                btn.innerHTML = origHtml;
                            }
                        });
                    });

                    // Keep the modal open when clicking pagination inside it:
                    // load the new page via AJAX into the modal body instead.
                    document.addEventListener('click', (e) => {
                        if (!this.open) return;
                        const link = e.target.closest('a');
                        if (!link || !this.$refs.body.contains(link)) return;
                        const nav = link.closest('nav[aria-label="Pagination Navigation"]');
                        if (!nav || !this.$refs.body.contains(nav)) return;
                        e.preventDefault();
                        const url = new URL(link.href, window.location.origin);
                        url.searchParams.set('modal', '1');
                        this.loading = true;
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then((r) => r.text())
                            .then((html) => {
                                this.$refs.body.innerHTML = html;
                                executeModalScripts(this.$refs.body);
                            })
                            .catch(() => {
                                this.$refs.body.innerHTML = '<div class="px-6 py-12 text-center text-gray-500"><i class="fa-solid fa-triangle-exclamation text-3xl text-red-400 mb-3"></i><p class="text-sm">Failed to load page.</p></div>';
                            })
                            .finally(() => { this.loading = false; });
                    });
                },
                openModal({ url, title }) {
                    if (!url) return;
                    this.title = title || 'Details';
                    this.currentUrl = url;
                    this.open = true;
                    this.loading = true;
                    this.$refs.body.innerHTML = '';
                    const sep = url.includes('?') ? '&' : '?';
                    fetch(url + sep + 'modal=1', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                        .then((r) => {
                            if (!r.ok) throw new Error('Request failed');
                            return r.text();
                        })
                        .then((html) => {
                            this.$refs.body.innerHTML = html;
                            executeModalScripts(this.$refs.body);
                        })
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

    {{-- Global double-submit guard: disables submit buttons after first submit --}}
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            // Skip forms inside the modal — they are handled by the AJAX handler
            if (form.closest('.modal-view-body')) return;
            if (form.dataset.submitting === '1') { e.preventDefault(); return; }
            const btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.dataset.noGuard === '1') return;
            form.dataset.submitting = '1';
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Saving...';
            setTimeout(() => { btn.disabled = false; form.dataset.submitting = ''; btn.innerHTML = btn.dataset.originalHtml; }, 10000);
        }, true);

        /**
         * Global animated delete confirmation dialog.
         * Usage: onsubmit="return confirmDelete(this)"
         */
        function confirmDelete(form) {
            // Prevent double-triggering
            if (form.dataset.cdOpen === '1') return false;
            form.dataset.cdOpen = '1';

            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4';
            overlay.innerHTML = `
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" id="cd-backdrop"></div>
                <div class="cd-modal-panel relative bg-white rounded-2xl shadow-2xl border border-gray-200 p-6 w-full max-w-sm">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-trash-can text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Delete this record?</h3>
                        <p class="text-sm text-gray-500 mb-6">This action cannot be undone. The record will be permanently removed.</p>
                        <div class="flex gap-3 w-full">
                            <button id="cd-cancel" type="button"
                                class="flex-1 px-4 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button id="cd-confirm" type="button"
                                class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 active:scale-95 transition">
                                <i class="fa-solid fa-trash-can mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);

            const cleanup = () => {
                form.dataset.cdOpen = '';
                overlay.remove();
            };

            overlay.querySelector('#cd-backdrop').addEventListener('click', cleanup);
            overlay.querySelector('#cd-cancel').addEventListener('click', cleanup);
            overlay.querySelector('#cd-confirm').addEventListener('click', () => {
                overlay.remove();
                form.dataset.cdOpen = '';
                form.dataset.skipConfirm = '1';
                form.submit();
            });

            return false;
        }
    </script>
</body>
</html>
