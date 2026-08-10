@extends('layouts.app')

@section('title', 'Procurement - DARTS')
@section('page-title', 'Procurement')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Procurement Records</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and track all procurement transactions.</p>
        </div>
        <a href="{{ route('procurement.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> New Procurement
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('procurement.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by invoice, item, or supplier..."
                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="w-full sm:w-48">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Status</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>
            @if($search || $status)
                <a href="{{ route('procurement.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">
                    <i class="fa-solid fa-times mr-1"></i> Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($procurements as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $p->invoice_no }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $p->supplier->supplier_name ?? '--' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate">{{ $p->item_description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $p->quantity }} {{ $p->unit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">&pound;{{ number_format($p->unit_price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">&pound;{{ number_format($p->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($p->status) {
                                    'Received' => 'success',
                                    'Pending' => 'warning',
                                    'Cancelled' => 'danger',
                                    default => 'default',
                                };
                            @endphp
                            <x-badge :type="$statusClass">{{ $p->status ?? '--' }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-2" x-data="{ open: false }">
                                <a href="#" data-url="{{ route('procurement.show', $p->transaction_id) }}" data-title="{{ $p->invoice_no }}"
                                   onclick="return openViewModal(this)"
                                   class="text-blue-600 hover:text-blue-800 transition" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('procurement.edit', $p->transaction_id) }}"
                                   class="text-amber-600 hover:text-amber-800 transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($p->status !== 'Received')
                                <form action="{{ route('procurement.receive', $p->transaction_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-emerald-600 hover:text-emerald-800 transition" title="Mark as Received"
                                            onclick="return confirm('Mark this procurement as received?')">
                                        <i class="fa-solid fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('procurement.destroy', $p->transaction_id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this procurement record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i class="fa-solid fa-file-invoice text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-sm">No procurement records found.</p>
                            @if($search || $status)
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your search or filter.</p>
                            @else
                                <a href="{{ route('procurement.create') }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium mt-2 inline-block">
                                    Create your first procurement record
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($procurements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $procurements->appends(['search' => $search, 'status' => $status])->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
