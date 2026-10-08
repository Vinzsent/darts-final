@extends('layouts.app')

@section('title', 'Property Reports - DARTS')
@section('page-title', 'Property Reports')

@section('content')
<div class="space-y-6" id="prRoot">
    <div class="rounded-xl bg-gradient-to-r from-emerald-800 to-emerald-600 text-white p-6 shadow-sm">
        <h2 class="text-xl font-bold flex items-center"><i class="fa-solid fa-chart-column mr-3"></i> {{ $mode === 'supply' ? 'Supply Reports' : 'Property Reports' }}</h2>
        <p class="text-emerald-100 text-sm mt-1">Filter, preview, and export {{ $mode === 'supply' ? 'supply' : 'property' }} data by report type</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-2 flex overflow-x-auto" role="tablist">
        @foreach($tabs as $key => $t)
            @php($active = $loop->first)
            <button type="button" data-tab="{{ $key }}" class="pr-tab flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 {{ $active ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-500 hover:text-gray-700' }} whitespace-nowrap"><i class="{{ $t[1] }}"></i> {{ $t[0] }}</button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 lg:sticky lg:top-24">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center mb-4"><i class="fa-solid fa-filter text-emerald-600 mr-2"></i> Filters</h3>
                <div class="pr-filters" data-for="inventory">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Category</label>
                    <select id="prCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Stock Status</label>
                    <div class="space-y-2 mb-4 text-sm">
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-stock" value="normal" checked> Normal</label>
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-stock" value="low"> Low Stock <span class="text-[11px] text-amber-600">(△ below reorder)</span></label>
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-stock" value="out"> Out of Stock <span class="text-[11px] text-red-500">(△ critical)</span></label>
                    </div>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Item Status</label>
                    <div class="space-y-2 mb-4 text-sm">
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-itemstatus" value="Active" checked> Active</label>
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-itemstatus" value="Inactive"> Inactive</label>
                        <label class="flex items-center gap-2.5"><input type="checkbox" class="pr-itemstatus" value="Discontinued"> Discontinued</label>
                    </div>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Search</label>
                    <input type="text" id="prSearchInv" placeholder="Item, brand, serial..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                <div class="pr-filters hidden" data-for="logs">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Date From</label>
                    <input type="date" id="prDateFrom" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Date To</label>
                    <input type="date" id="prDateTo" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Movement Type</label>
                    <select id="prMovement" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Movements</option>
                        <option value="IN">IN</option>
                        <option value="OUT">OUT</option>
                    </select>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Search</label>
                    <input type="text" id="prSearchLogs" placeholder="Item, requester, notes..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

                @if(isset($tabs['aircon']))
                <div class="pr-filters hidden" data-for="aircon">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Brand</label>
                    <select id="prBrand" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Brands</option>
                        @foreach($brands as $b)
                            <option value="{{ $b }}">{{ $b }}</option>
                        @endforeach
                    </select>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Status</label>
                    <select id="prAirconStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-4 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Statuses</option>
                        @foreach($airconStatuses as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>

                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1.5">Search</label>
                    <input type="text" id="prSearchAircon" placeholder="Item no., serial, location..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-[480px] flex flex-col">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900 flex items-center"><i class="fa-regular fa-newspaper text-emerald-600 mr-2"></i> Preview</h3>
                    <div class="flex gap-2">
                        <button type="button" id="prPdf" disabled class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-file-pdf mr-1.5"></i> PDF</button>
                        <button type="button" id="prCsv" disabled class="inline-flex items-center px-3.5 py-2 text-xs font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition disabled:opacity-40 disabled:cursor-not-allowed"><i class="fa-solid fa-file-csv mr-1.5"></i> CSV</button>
                    </div>
                </div>
                <div id="previewArea" class="flex-1 flex items-center justify-center p-10">
                    <div class="text-center">
                        <i class="fa-regular fa-eye text-5xl text-gray-200 mb-4"></i>
                        <p class="text-gray-400 text-sm">Set your filters and click <span class="font-semibold text-emerald-700">Preview</span> to generate the report.</p>
                    </div>
                </div>
            </div>
            <button type="button" id="prPreviewBtn" class="mt-5 w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition shadow-sm">
                <i class="fa-regular fa-eye mr-2"></i> Preview
            </button>
        </div>
    </div>
</div>
<script>
(function () {
    const root = document.getElementById('prRoot');
    const area = document.getElementById('previewArea');
    const previewBtn = document.getElementById('prPreviewBtn');
    const tabs = root.querySelectorAll('.pr-tab');
    let currentType = 'inventory';
    let hasPreview = false;

    function checked(selector) {
        return Array.from(root.querySelectorAll(selector + ':checked')).map((c) => c.value);
    }

    function buildParams(page) {
        const p = new URLSearchParams();
        p.set('report_type', currentType);
        if (page) p.set('page', page);
        if (currentType === 'inventory') {
            p.set('category', document.getElementById('prCategory').value);
            checked('.pr-stock').forEach((v) => p.append('stock_status[]', v));
            checked('.pr-itemstatus').forEach((v) => p.append('item_status[]', v));
            p.set('search', document.getElementById('prSearchInv').value.trim());
        } else if (currentType === 'logs') {
            p.set('date_from', document.getElementById('prDateFrom').value);
            p.set('date_to', document.getElementById('prDateTo').value);
            p.set('movement_type', document.getElementById('prMovement').value);
            p.set('search', document.getElementById('prSearchLogs').value.trim());
        } else {
            p.set('brand', document.getElementById('prBrand').value);
            p.set('aircon_status', document.getElementById('prAirconStatus').value);
            p.set('search', document.getElementById('prSearchAircon').value.trim());
        }
        return p;
    }

    function loadPreview(page) {
        const spin = '<div class="text-center"><i class="fa-solid fa-circle-notch fa-spin text-3xl text-emerald-600 mb-3"></i><p class="text-gray-400 text-sm">Generating preview...</p></div>';
        area.innerHTML = spin;
        area.className = 'flex-1 flex items-center justify-center p-10';
        previewBtn.disabled = true;

        fetch('{!! $previewUrl !!}?' + buildParams(page).toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((r) => { if (!r.ok) throw new Error('Failed'); return r.text(); })
            .then((html) => {
                area.innerHTML = html;
                area.className = 'flex-1';
                if (window.enhanceTables) window.enhanceTables(area);
                hasPreview = true;
                document.getElementById('prPdf').disabled = false;
                document.getElementById('prCsv').disabled = false;
            })
            .catch(() => {
                area.innerHTML = '<div class="text-center"><p class="text-red-500 text-sm">Could not load the report. Please try again.</p></div>';
            })
            .finally(() => { previewBtn.disabled = false; });
    }

    function switchTab(type) {
        currentType = type;
        tabs.forEach((t) => {
            const active = t.dataset.tab === type;
            t.classList.toggle('border-emerald-600', active);
            t.classList.toggle('text-emerald-700', active);
            t.classList.toggle('border-transparent', !active);
            t.classList.toggle('text-gray-500', !active);
        });
        root.querySelectorAll('.pr-filters').forEach((f) => {
            f.classList.toggle('hidden', f.dataset.for !== type);
        });
        hasPreview = false;
        document.getElementById('prPdf').disabled = true;
        document.getElementById('prCsv').disabled = true;
        area.className = 'flex-1 flex items-center justify-center p-10';
        area.innerHTML = '<div class="text-center"><i class="fa-regular fa-eye text-5xl text-gray-200 mb-4"></i><p class="text-gray-400 text-sm">Set your filters and click <span class="font-semibold text-emerald-700">Preview</span> to generate the report.</p></div>';
    }

    tabs.forEach((t) => t.addEventListener('click', () => switchTab(t.dataset.tab)));
    previewBtn.addEventListener('click', () => loadPreview(1));

    area.addEventListener('click', (e) => {
        const link = e.target.closest('nav[aria-label="Pagination Navigation"] a');
        if (!link) return;
        e.preventDefault();
        e.stopPropagation();
        area.style.opacity = '0.6';
        fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then((r) => r.text())
            .then((html) => {
                area.innerHTML = html;
                if (window.enhanceTables) window.enhanceTables(area);
            })
            .catch(() => { window.location.href = link.href; })
            .finally(() => { area.style.opacity = ''; });
    });

    function exportReport(format) {
        if (!hasPreview) return;
        const p = buildParams();
        p.set('format', format);
        window.open('{!! $exportUrl !!}?' + p.toString(), '_blank');
    }
    document.getElementById('prPdf').addEventListener('click', () => exportReport('pdf'));
    document.getElementById('prCsv').addEventListener('click', () => exportReport('csv'));
})();
</script>
@endsection
