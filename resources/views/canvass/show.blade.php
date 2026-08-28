@extends('layouts.app')

@section('title', 'Canvass Details - DARTS')
@section('page-title', 'Canvass Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Canvass</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">#{{ $canvass->canvass_id }}</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('canvass.edit', $canvass->canvass_id) }}" class="inline-flex items-center rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:bg-amber-100">
                <i class="fa-solid fa-pen-to-square mr-2"></i>
                Edit
            </a>
            <a href="{{ route('canvass.index') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Date</p>
            <p class="mt-3 text-xl font-bold text-slate-900">{{ \Carbon\Carbon::parse($canvass->canvass_date)->format('M d, Y') }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Status</p>
            <div class="mt-3">
                @php
                    $badge = match($canvass->status ?? '') {
                        'Canvassed' => 'bg-blue-100 text-blue-700',
                        'Completed' => 'bg-amber-100 text-amber-700',
                        'Approved' => 'bg-emerald-100 text-emerald-700',
                        'Cancelled' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-700',
                    };
                @endphp
                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge }}">{{ $canvass->status }}</span>
            </div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Canvassed By</p>
            <p class="mt-3 text-lg font-bold text-slate-900">{{ $canvass->canvassedBy?->display_name ?? 'N/A' }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Total</p>
            <p class="mt-3 text-xl font-bold text-slate-900">₱{{ number_format((float) ($canvass->total_amount ?? 0), 2) }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="text-lg font-semibold text-slate-900">Notes</h3>
        <p class="mt-3 text-sm leading-6 text-slate-600">{{ $canvass->notes ?: 'No additional notes provided.' }}</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 bg-slate-50 px-5 py-3">
            <h3 class="text-lg font-semibold text-slate-900">Items ({{ $canvass->items->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">#</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Supplier</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Department</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Campus</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Description</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Qty</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Unit Cost</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($canvass->items as $item)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-900">{{ $item->item_number }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $item->supplier_name }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $item->department ?: 'N/A' }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $item->campus ?: 'N/A' }}</td>
                            <td class="px-5 py-4 text-slate-700">{{ $item->item_description }}</td>
                            <td class="px-5 py-4 text-right text-slate-700">{{ number_format((float) $item->quantity, 2) }}</td>
                            <td class="px-5 py-4 text-right text-slate-700">₱{{ number_format((float) $item->unit_cost, 2) }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-slate-900">₱{{ number_format((float) $item->total_cost, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-slate-500">No items in this canvass.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
