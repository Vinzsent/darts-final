@php
    $titles = $mode === 'supply'
        ? ['inventory' => 'Supply Inventory Report', 'logs' => 'Supply Stock Movement Logs', 'aircon' => 'Aircon Report']
        : ['inventory' => 'Property Inventory Report', 'logs' => 'Stock Movement Logs', 'aircon' => 'Aircon Report'];
    $itemOf = $mode === 'supply'
        ? (fn ($l) => $l->inventory->item_name ?? '—')
        : (fn ($l) => $l->property->item_name ?? '—');
@endphp
<div class="px-6 py-5 border-b border-gray-100">
    <h3 class="text-lg font-semibold text-gray-900">{{ $titles[$type] }}</h3>
    <p class="text-xs text-gray-400 mt-0.5">Generated: {{ $generated->format('F j, Y \a\t g:i A') }}</p>
</div>

<div class="px-6 py-4 overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden w-full">
        <thead>
            <tr class="bg-emerald-800 text-white text-xs uppercase" style="background:#065f46;">
@if($type === 'inventory')
                <th class="px-2 py-2.5 text-left font-semibold">Item Name</th>
                <th class="px-2 py-2.5 text-left font-semibold">Category</th>
                <th class="px-2 py-2.5 text-left font-semibold">Stock</th>
                <th class="px-2 py-2.5 text-left font-semibold">Reorder Lvl</th>
                <th class="px-2 py-2.5 text-left font-semibold">Unit</th>
                <th class="px-2 py-2.5 text-left font-semibold">Brand</th>
                <th class="px-2 py-2.5 text-left font-semibold">Type</th>
                <th class="px-2 py-2.5 text-left font-semibold">Status</th>
                <th class="px-2 py-2.5 text-right font-semibold">Unit Cost</th>
                <th class="px-2 py-2.5 text-right font-semibold">Total Value</th>
@elseif($type === 'logs')
                <th class="px-2 py-2.5 text-left font-semibold">Date</th>
                <th class="px-2 py-2.5 text-left font-semibold">Item</th>
                <th class="px-2 py-2.5 text-left font-semibold">Type</th>
                <th class="px-2 py-2.5 text-right font-semibold">Qty</th>
                <th class="px-2 py-2.5 text-center font-semibold">Previous → New</th>
                <th class="px-2 py-2.5 text-left font-semibold">Received By</th>
                <th class="px-2 py-2.5 text-left font-semibold">Notes</th>
@else
                <th class="px-2 py-2.5 text-left font-semibold">Item No.</th>
                <th class="px-2 py-2.5 text-left font-semibold">Brand / Model</th>
                <th class="px-2 py-2.5 text-left font-semibold">Capacity</th>
                <th class="px-2 py-2.5 text-left font-semibold">Serial No.</th>
                <th class="px-2 py-2.5 text-left font-semibold">Location</th>
                <th class="px-2 py-2.5 text-left font-semibold">Status</th>
                <th class="px-2 py-2.5 text-left font-semibold">Last Service</th>
                <th class="px-2 py-2.5 text-left font-semibold">Next Service</th>
                <th class="px-2 py-2.5 text-right font-semibold">Price</th>
@endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($items as $i)
                <tr class="hover:bg-gray-50">
@if($type === 'inventory')
                    <td class="px-2 py-2 text-gray-800">{{ $i->item_name }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->category }}</td>
                    <td class="px-2 py-2 text-gray-800">{{ $i->current_stock }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->reorder_level }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->unit }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->brand }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->type }}</td>
                    <td class="px-3 py-2"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $i->status === 'Active' ? 'bg-emerald-100 text-emerald-800' : ($i->status === 'Inactive' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-700') }}">{{ $i->status }}</span></td>
                    <td class="px-2 py-2 text-right text-gray-700">₱{{ number_format((float) $i->unit_cost, 2) }}</td>
                    <td class="px-2 py-2 text-right text-gray-800 font-medium">₱{{ number_format((float) $i->current_stock * (float) $i->unit_cost, 2) }}</td>
@elseif($type === 'logs')
                    <td class="px-2 py-2 text-gray-600 whitespace-nowrap">{{ $i->date_created }}</td>
                    <td class="px-2 py-2 text-gray-800">{{ $itemOf($i) }}</td>
                    <td class="px-3 py-2"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $i->movement_type === 'IN' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $i->movement_type }}</span></td>
                    <td class="px-2 py-2 text-right text-gray-800 font-medium">{{ $i->quantity }}</td>
                    <td class="px-2 py-2 text-center text-gray-600">{{ $i->previous_stock }} → {{ $i->new_stock }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->receiver }}</td>
                    <td class="px-2 py-2 text-gray-500 max-w-[220px] truncate">{{ $i->notes }}</td>
@else
                    <td class="px-2 py-2 text-gray-800">{{ $i->item_number }}</td>
                    <td class="px-2 py-2 text-gray-800">{{ $i->brand }} {{ $i->model }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->capacity }}</td>
                    <td class="px-2 py-2 text-gray-600 font-mono text-xs">{{ $i->serial_number }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->location }}@if($i->area) <span class="text-gray-400">· {{ $i->area }}</span>@endif</td>
                    <td class="px-3 py-2"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $i->status === 'Working' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $i->status }}</span></td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->maintenance->sortByDesc('service_date')->first()->service_date ?? '—' }}</td>
                    <td class="px-2 py-2 text-gray-600">{{ $i->maintenance->sortByDesc('next_scheduled_date')->first()->next_scheduled_date ?? '—' }}</td>
                    <td class="px-2 py-2 text-right text-gray-800 font-medium">₱{{ number_format((float) $i->purchase_price, 2) }}</td>
@endif
                </tr>
            @empty
                <tr><td colspan="10" class="px-3 py-10 text-center text-gray-400">No results found.</td></tr>
            @endforelse
@if($type === 'inventory' && $items->count() > 0)
            <tr class="bg-emerald-50 font-semibold">
                <td colspan="9" class="px-3 py-2.5 text-right text-gray-800">Total Valuation:</td>
                <td class="px-3 py-2.5 text-right text-emerald-700">₱{{ number_format($totals['valuation'] ?? 0, 2) }}</td>
            </tr>
@endif
        </tbody>
    </table>
</div>

@if($items->hasPages())
<div class="px-6 py-3 border-t border-gray-100">
    {{ $items->appends(request()->query())->onEachSide(1)->links() }}
</div>
@endif
