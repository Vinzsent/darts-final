@extends('layouts.app')

@section('title', 'Inventory - DARTS')
@section('page-title', 'Inventory')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Manage all inventory items in the system.</p>
        </div>
        <a href="{{ route('inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i> Add Item
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('inventory.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Item name, brand..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All Statuses</option>
                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ $status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="Discontinued" {{ $status == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition">
                    <i class="fa-solid fa-rotate mr-1"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-9 h-9 bg-emerald-100 text-emerald-700 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fa-solid fa-box text-sm"></i>
                                </div>
                                <div>
                                    <a href="#" data-url="{{ route('inventory.show', $item->inventory_id) }}" data-title="{{ $item->item_name }}" onclick="return openViewModal(this)" class="text-sm font-medium text-gray-900 hover:text-emerald-600">
                                        {{ $item->item_name }}
                                    </a>
                                    @if($item->description)
                                        <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ Str::limit($item->description, 40) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->category ?? '--' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->brand ?? '--' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold {{ $item->is_low_stock ? ($item->current_stock <= 0 ? 'text-red-600' : 'text-amber-600') : 'text-gray-900' }}">
                                    {{ number_format($item->current_stock) }}
                                </span>
                                @if($item->unit)
                                    <span class="text-xs text-gray-400">{{ $item->unit }}</span>
                                @endif
                                @if($item->is_low_stock)
                                    <i class="fa-solid {{ $item->current_stock <= 0 ? 'fa-circle-exclamation text-red-500' : 'fa-triangle-exclamation text-amber-500' }}"></i>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->status === 'Active')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Active
                                </span>
                            @elseif($item->status === 'Inactive')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                    Inactive
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Discontinued
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="#" data-url="{{ route('inventory.show', $item->inventory_id) }}" data-title="{{ $item->item_name }}" onclick="return openViewModal(this)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('inventory.edit', $item->inventory_id) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('inventory.destroy', $item->inventory_id) }}" method="POST" onsubmit="return confirm('Delete this inventory item?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm">No inventory items found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
