@extends('layouts.app')

@section('title', 'Aircons - DARTS')
@section('page-title', 'Aircons')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <p class="text-sm text-gray-500">Air conditioning units — locations, maintenance, and service history.</p>
        <a href="{{ route('aircon.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i> Add Aircon
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Units</span>
                <i class="fa-solid fa-fan text-emerald-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Working</span>
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['working'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Under Repair</span>
                <i class="fa-solid fa-screwdriver-wrench text-amber-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['repair'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Acquisition Value</span>
                <i class="fa-solid fa-coins text-emerald-600"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900 mt-1">₱{{ number_format($stats['value'], 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('aircon.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Brand, serial, location..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Brand</label>
                <select name="brand" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Brands</option>
                    @foreach($brands as $b)
                        <option value="{{ $b }}" {{ $brand == $b ? 'selected' : '' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ $status == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('aircon.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition">
                    <i class="fa-solid fa-rotate mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>
    @forelse($items as $item)
        @php
            $img = $item->images->first();
            $last = $item->maintenance->sortByDesc('service_date')->first();
            $next = $item->maintenance->sortByDesc('next_scheduled_date')->first();
            $stClass = 'bg-gray-100 text-gray-600';
            if ($item->status === 'Working') { $stClass = 'bg-emerald-100 text-emerald-800'; }
            elseif ($item->status === 'Under Repair' || $item->status === 'Defective') { $stClass = 'bg-red-100 text-red-700'; }
            elseif ($item->status === 'Under Maintenance') { $stClass = 'bg-amber-100 text-amber-800'; }
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="flex flex-col sm:flex-row">
                <div class="sm:w-44 h-40 sm:h-auto bg-gray-100 flex items-center justify-center shrink-0">
                    @if($img)
                        <img src="{{ $img->url ?? '' }}" alt="{{ $item->brand }} {{ $item->model }}" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none';this.parentElement.innerHTML='<i class=&quot;fa-solid fa-fan text-4xl text-gray-300&quot;></i>';">
                    @else
                        <i class="fa-solid fa-fan text-4xl text-gray-300"></i>
                    @endif
                </div>
                <div class="flex-1 p-5">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $item->brand }} {{ $item->model }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $item->item_number ? 'Item No. '.$item->item_number : 'Unit #'.$item->aircon_id }}
                                @if($item->capacity) · {{ $item->capacity }} @endif
                                @if($item->type) · {{ $item->type }} @endif
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stClass }}">{{ $item->status }}</span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Location</p>
                            <p class="text-gray-700 truncate">{{ $item->location ?? '—' }}</p>
                            @if($item->area) <p class="text-xs text-gray-400">{{ $item->area }}</p> @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Serial No.</p>
                            <p class="text-gray-700 font-mono text-xs truncate">{{ $item->serial_number ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Last Service</p>
                            @if($last)
                                <p class="text-gray-700">{{ $last->service_date }} <span class="text-xs text-gray-400">({{ $last->service_type }})</span></p>
                            @else
                                <p class="text-gray-400">No service recorded</p>
                            @endif
                        </div>
                    </div>
                    @if($next && $next->next_scheduled_date)
                        <div class="mt-3 inline-flex items-center text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg px-2.5 py-1">
                            <i class="fa-regular fa-calendar-check mr-1.5"></i> Next service: {{ $next->next_scheduled_date }}
                        </div>
                    @endif

                    <div class="mt-4 pt-3 border-t border-gray-200 flex items-center gap-2 justify-end">
                        <a href="{{ route('aircon.show', $item->aircon_id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition"><i class="fa-solid fa-eye mr-1.5"></i> View</a>
                        <a href="{{ route('aircon.edit', $item->aircon_id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition"><i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit</a>
                        <form action="{{ route('aircon.destroy', $item->aircon_id) }}" method="POST" onsubmit="return confirmDelete(this)" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition"><i class="fa-solid fa-trash-can mr-1.5"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i class="fa-solid fa-fan text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm">No aircon units found.</p>
        </div>
    @endforelse

    @if($items->hasPages())
    <div class="px-1">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
