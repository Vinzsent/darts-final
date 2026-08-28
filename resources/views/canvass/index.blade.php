@extends('layouts.app')

@section('title', 'Canvass - DARTS')
@section('page-title', 'Canvass Management')

@section('content')
<div class="space-y-6 min-w-0">
    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Supplier comparison</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Canvass Management</h2>
        </div>

        <a href="{{ route('canvass.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            <i class="fa-solid fa-plus mr-2"></i>
            Create Canvass
        </a>
    </div>

    @php
        $totalCanvass = $canvasses->total();
        $canvassedCount = $canvasses->where('status', 'Canvassed')->count();
        $approvedCount = $canvasses->where('status', 'Approved')->count();
        $totalValue = (float) ($canvasses->sum('total_amount') ?? 0);
    @endphp

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Total</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-scale-balanced text-sm"></i>
                </span>
            </div>
            <div class="mt-4 text-3xl font-bold text-slate-900">{{ $totalCanvass }}</div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Canvassed</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                    <i class="fa-solid fa-file-circle-check text-sm"></i>
                </span>
            </div>
            <div class="mt-4 text-3xl font-bold text-blue-600">{{ $canvassedCount }}</div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Approved</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-green-50 text-green-600">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </span>
            </div>
            <div class="mt-4 text-3xl font-bold text-green-600">{{ $approvedCount }}</div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-[0.15em] text-slate-500">Value</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-peso-sign text-sm"></i>
                </span>
            </div>
            <div class="mt-4 text-2xl font-bold text-slate-900">₱{{ number_format($totalValue, 2) }}</div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" action="{{ route('canvass.index') }}" class="grid gap-3 md:grid-cols-[minmax(0,1.5fr)_minmax(180px,0.8fr)_auto]">
            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Canvass ID, notes, supplier, item description..." class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>

            <div>
                <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">All Statuses</option>
                    <option value="Canvassed" {{ ($status ?? '') == 'Canvassed' ? 'selected' : '' }}>Canvassed</option>
                    <option value="Completed" {{ ($status ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Approved" {{ ($status ?? '') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Cancelled" {{ ($status ?? '') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <i class="fa-solid fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('canvass.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                    <i class="fa-solid fa-rotate-right mr-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Canvass ID</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Date</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Items</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Total Amount</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Created By</th>
                        <th class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($canvasses as $canvass)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $canvass->canvass_id }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $canvass->canvass_date ? \Carbon\Carbon::parse($canvass->canvass_date)->format('M d, Y') : '--' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $canvass->items->count() }} item(s)</td>
                            <td class="px-5 py-4 font-semibold text-slate-900">₱{{ number_format((float) ($canvass->total_amount ?? 0), 2) }}</td>
                            <td class="px-5 py-4">
                                @php
                                    $badge = match($canvass->status ?? '') {
                                        'Canvassed' => 'bg-blue-100 text-blue-700',
                                        'Completed' => 'bg-amber-100 text-amber-700',
                                        'Approved' => 'bg-emerald-100 text-emerald-700',
                                        'Cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge }}">{{ $canvass->status ?? 'N/A' }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $canvass->creator->display_name ?? 'N/A' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('canvass.show', $canvass->canvass_id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 transition hover:bg-blue-100" title="View">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('canvass.edit', $canvass->canvass_id) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-600 transition hover:bg-amber-100" title="Edit">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('canvass.destroy', $canvass->canvass_id) }}" method="POST" onsubmit="return confirm('Delete this canvass?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100" title="Delete">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-scale-balanced text-4xl"></i>
                                    <p class="mt-4 text-base font-medium text-slate-500">No canvass records found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($canvasses->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-4 py-3">
                {{ $canvasses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
