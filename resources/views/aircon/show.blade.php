@extends('layouts.app')

@section('title', 'Aircon Details - DARTS')
@section('page-title', 'Aircon Details')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('aircon.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-emerald-700 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Aircons
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('aircon.edit', $item->aircon_id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit
            </a>
            <form action="{{ route('aircon.destroy', $item->aircon_id) }}" method="POST" onsubmit="return confirmDelete(this)" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                    <i class="fa-solid fa-trash-can mr-1.5"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: images + identity --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="h-56 bg-gray-100 flex items-center justify-center">
                    @if($item->images->first())
                        <img id="mainImg" src="{{ $item->images->first()->url ?? '' }}" alt="{{ $item->brand }} {{ $item->model }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<i class=&quot;fa-solid fa-fan text-5xl text-gray-300&quot;></i>';">
                    @else
                        <i class="fa-solid fa-fan text-5xl text-gray-300"></i>
                    @endif
                </div>
                @if($item->images->count() > 1)
                <div class="flex gap-2 p-3 overflow-x-auto">
                    @foreach($item->images as $img)
                        <button type="button" onclick="document.getElementById('mainImg').src='{{ $img->url ?? '' }}'" class="w-14 h-14 rounded-lg overflow-hidden border border-gray-200 shrink-0 hover:border-emerald-500 transition">
                            <img src="{{ $img->url ?? '' }}" class="w-full h-full object-cover" onerror="this.parentElement.style.display='none';">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                @php
                    $stClass = 'bg-gray-100 text-gray-600';
                    if ($item->status === 'Working') { $stClass = 'bg-emerald-100 text-emerald-800'; }
                    elseif ($item->status === 'Under Repair' || $item->status === 'Defective') { $stClass = 'bg-red-100 text-red-700'; }
                    elseif ($item->status === 'Under Maintenance') { $stClass = 'bg-amber-100 text-amber-800'; }
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-gray-900">Unit Information</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $stClass }}">{{ $item->status }}</span>
                </div>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Item No.</dt><dd class="text-gray-800 font-medium text-right">{{ $item->item_number ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Brand / Model</dt><dd class="text-gray-800 text-right">{{ $item->brand }} {{ $item->model }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Type / Capacity</dt><dd class="text-gray-800 text-right">{{ $item->type }} {{ $item->capacity }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Serial No.</dt><dd class="text-gray-800 font-mono text-xs text-right">{{ $item->serial_number ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Location</dt><dd class="text-gray-800 text-right">{{ $item->location ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Area</dt><dd class="text-gray-800 text-right">{{ $item->area ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Campus</dt><dd class="text-gray-800 text-right">{{ $item->campus ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Building</dt><dd class="text-gray-800 text-right">{{ $item->bldg ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Installation Date</dt><dd class="text-gray-800 text-right">{{ $item->installation_date ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4"><dt class="text-gray-400">Warranty Expiry</dt><dd class="text-gray-800 text-right">{{ $item->warranty_expiry ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-4 border-t border-gray-100 pt-3"><dt class="text-gray-400">Purchase Price</dt><dd class="text-emerald-700 font-bold text-right">₱{{ number_format((float) $item->purchase_price, 2) }}</dd></div>
                </dl>
                @if($item->notes)
                    <p class="mt-4 text-xs text-gray-500 bg-gray-50 rounded-lg p-3">{{ $item->notes }}</p>
                @endif
            </div>
        </div>
        {{-- Right: maintenance history timeline --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-1"><i class="fa-solid fa-screwdriver-wrench text-emerald-600 mr-2"></i> Maintenance History</h3>
                <p class="text-xs text-gray-400 mb-6">Service records from aircon_maintenance.</p>

                @if($item->maintenance->isEmpty())
                    <div class="text-center py-12">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 text-sm">No maintenance records yet.</p>
                    </div>
                @else
                    <ol class="relative border-l-2 border-emerald-100 ml-3 space-y-8">
                        @foreach($item->maintenance->sortByDesc('service_date') as $m)
                            <li class="ml-6">
                                <span class="absolute -left-[9px] flex w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-100"></span>
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $m->service_type ?: 'Service' }}</h4>
                                    <span class="text-xs text-gray-400">{{ $m->service_date }}</span>
                                    @if($m->next_scheduled_date)
                                        <span class="inline-flex items-center text-[11px] font-medium text-emerald-700 bg-emerald-50 rounded-full px-2 py-0.5">
                                            <i class="fa-regular fa-calendar-check mr-1"></i> Next: {{ $m->next_scheduled_date }}
                                        </span>
                                    @endif
                                </div>
                                @if($m->technician)
                                    <p class="text-xs text-gray-500"><i class="fa-solid fa-user-gear mr-1 text-gray-300"></i> {{ $m->technician }}</p>
                                @endif
                                @if($m->remarks)
                                    <p class="text-sm text-gray-600 mt-1.5 bg-gray-50 rounded-lg p-3">{{ $m->remarks }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
</div>
@endsection
