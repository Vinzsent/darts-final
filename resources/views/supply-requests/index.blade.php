@extends('layouts.app')

@section('title', 'Supply Requests - DARTS')
@section('page-title', 'Supply Requests')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">Manage and track all supply requests</p>
        </div>
        <a href="{{ route('supply-requests.create') }}"
           class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> New Request
        </a>
    </div>

    {{-- Status Tabs --}}
    @php
        $tabs = [
            'all' => ['label' => 'All', 'icon' => 'fa-solid fa-list'],
            'Pending' => ['label' => 'Pending', 'icon' => 'fa-solid fa-clock'],
            'Noted' => ['label' => 'Noted', 'icon' => 'fa-solid fa-eye'],
            'Checked' => ['label' => 'Checked', 'icon' => 'fa-solid fa-check-double'],
            'Verified' => ['label' => 'Verified', 'icon' => 'fa-solid fa-shield'],
            'Approved' => ['label' => 'Approved', 'icon' => 'fa-solid fa-thumbs-up'],
            'Issued' => ['label' => 'Issued', 'icon' => 'fa-solid fa-check-circle'],
        ];
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto">
                @foreach($tabs as $key => $tab)
                    <a href="{{ route('supply-requests.index', array_merge(request()->query(), ['status' => $key])) }}"
                       class="flex items-center space-x-2 px-4 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition
                       {{ $status == $key ? 'border-emerald-600 text-emerald-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        <i class="{{ $tab['icon'] }}"></i>
                        <span>{{ $tab['label'] }}</span>
                        @if($key !== 'all' && isset($counts[$key]))
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $status == $key ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $counts[$key] }}
                            </span>
                        @elseif($key === 'all')
                            <span class="ml-1 px-2 py-0.5 text-xs rounded-full {{ $status == $key ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $supplyRequests->total() }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        {{-- Filters --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <form method="GET" action="{{ route('supply-requests.index') }}" class="flex flex-wrap gap-3">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" placeholder="Search requests..." value="{{ $search }}"
                               class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div>
                    <select name="department" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">All Departments</option>
                        @foreach(['Academic', 'Administration', 'Finance', 'HR', 'IT', 'Logistics'] as $dept)
                            <option value="{{ $dept }}" {{ $department == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
                @if($search || $department)
                    <a href="{{ route('supply-requests.index', ['status' => $status]) }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-red-200 transition">
                        <i class="fa-solid fa-times mr-1"></i> Clear
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">ID</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Item</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Requestor</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Department</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Date Needed</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Qty</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Status</th>
                        <th class="text-right px-4 py-3 font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($supplyRequests as $sr)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">#{{ $sr->request_id }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $sr->item_name }}</div>
                                <div class="text-xs text-gray-500">{{ Str::limit($sr->purpose, 40) }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $sr->user?->display_name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $sr->department_unit }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($sr->date_needed)->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $sr->quantity_requested }} {{ $sr->unit }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800',
                                        'Noted' => 'bg-blue-100 text-blue-800',
                                        'Checked' => 'bg-indigo-100 text-indigo-800',
                                        'Verified' => 'bg-purple-100 text-purple-800',
                                        'Approved' => 'bg-emerald-100 text-emerald-800',
                                        'Issued' => 'bg-green-100 text-green-800',
                                        'Rejected' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $statusColors[$sr->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                    {{ $sr->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="#" data-url="{{ route('supply-requests.show', $sr->request_id) }}" data-title="#{{ $sr->request_id }}"
                                       onclick="return openViewModal(this)"
                                       class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if(in_array($sr->status, ['Pending', 'Rejected']))
                                        <a href="{{ route('supply-requests.edit', $sr->request_id) }}"
                                           class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('supply-requests.destroy', $sr->request_id) }}"
                                              onsubmit="return confirm('Delete this request?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <i class="fa-solid fa-clipboard-list text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium text-gray-400">No supply requests found</p>
                                <p class="text-sm mt-1">Create a new request to get started.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($supplyRequests->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $supplyRequests->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
