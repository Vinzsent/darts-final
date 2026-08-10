@extends('layouts.app')

@section('title', 'Suppliers - DARTS')
@section('page-title', 'Suppliers')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Manage your supplier records.</p>
        <a href="{{ route('suppliers.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> New Supplier
        </a>
    </div>

    {{-- Data Table --}}
    <x-data-table :headers="['Supplier Name', 'Contact Person', 'Contact Number', 'Status']" :searchable="true">
        <x-slot name="body">
            @forelse($suppliers as $supplier)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-9 h-9 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                            {{ substr($supplier->supplier_name, 0, 1) }}
                        </div>
                        <div>
                            <a href="#" data-url="{{ route('suppliers.show', $supplier->supplier_id) }}" data-title="{{ $supplier->supplier_name }}" onclick="return openViewModal(this)" class="text-sm font-medium text-gray-900 hover:text-emerald-600">
                                {{ $supplier->supplier_name }}
                            </a>
                            @if($supplier->email_address)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $supplier->email_address }}</p>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $supplier->contact_person ?? '—' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $supplier->contact_number ?? $supplier->landline_number ?? '—' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                    $badgeType = match(strtoupper($supplier->status ?? '')) {
                        'ACTIVE' => 'success',
                        'INACTIVE' => 'danger',
                        'PENDING' => 'warning',
                        default => 'default',
                    };
                    @endphp
                    <x-badge :type="$badgeType">{{ $supplier->status ?? 'N/A' }}</x-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="#" data-url="{{ route('suppliers.show', $supplier->supplier_id) }}" data-title="{{ $supplier->supplier_name }}" onclick="return openViewModal(this)" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50 transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" onsubmit="return confirm('Are you sure you want to delete this supplier?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 p-1.5 rounded-lg hover:bg-red-50 transition" title="Delete">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center text-gray-400">
                        <i class="fa-solid fa-truck text-4xl mb-3"></i>
                        <p class="text-sm font-medium">No suppliers found</p>
                        <p class="text-xs mt-1">Get started by creating a new supplier.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $suppliers->appends(['search' => $search])->links() }}
        </x-slot>
    </x-data-table>
</div>
@endsection
