@extends('layouts.app')

@section('title', 'Dashboard - DARTS')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Welcome --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Welcome back, {{ Auth::user()->display_name }}
        </h2>
        <p class="text-gray-500 text-sm mt-1">Here's what's happening with DARTS today.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-dashboard-stat title="Total Inventory" :value="$stats['total_inventory']" icon="fa-boxes-stacked" color="emerald" />
        <x-dashboard-stat title="Suppliers" :value="$stats['total_suppliers']" icon="fa-truck" color="blue" />
        <x-dashboard-stat title="Pending Requests" :value="$stats['pending_requests']" icon="fa-clipboard-list" color="amber" />
        <x-dashboard-stat title="Pending Procurement" :value="$stats['pending_procurement']" icon="fa-file-invoice" color="rose" />
    </div>

    {{-- Alerts Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Low Stock Alerts</h3>
                <span class="text-2xl font-bold text-amber-600">{{ $stats['low_stock_items'] }}</span>
            </div>
            @if($stats['low_stock_items'] > 0)
                <p class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                    {{ $stats['low_stock_items'] }} item(s) are below reorder level
                </p>
            @else
                <p class="text-sm text-gray-500">All items are well-stocked.</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Out of Stock</h3>
                <span class="text-2xl font-bold text-red-600">{{ $stats['out_of_stock'] }}</span>
            </div>
            @if($stats['out_of_stock'] > 0)
                <p class="text-sm text-red-600 bg-red-50 rounded-lg p-3">
                    <i class="fa-solid fa-circle-xmark mr-2"></i>
                    {{ $stats['out_of_stock'] }} item(s) need restocking
                </p>
            @else
                <p class="text-sm text-gray-500">No items are out of stock.</p>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> Manage Inventory
            </a>
            <a href="{{ route('suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> Manage Suppliers
            </a>
            <a href="{{ route('procurement.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition">
                <i class="fa-solid fa-plus mr-2"></i> View Procurement
            </a>
        </div>
    </div>
</div>
@endsection
