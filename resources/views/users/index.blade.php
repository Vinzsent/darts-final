@extends('layouts.app')

@section('title', 'Users - DARTS')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Manage system users, roles, and permissions.</p>
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm rounded-lg hover:bg-emerald-700 transition">
            <i class="fa-solid fa-plus mr-2"></i> New User
        </a>
    </div>

    {{-- Data Table --}}
    <x-data-table :headers="['User', 'User Type', 'Status', 'Email', 'Username']" :searchable="true">
        <x-slot name="body">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-9 h-9 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                            {{ substr($user->first_name ?? 'U', 0, 1) }}{{ substr($user->last_name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->display_name }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $user->username }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                    $badgeType = match(strtolower($user->user_type ?? '')) {
                        'admin', 'administrator' => 'danger',
                        'supply in-charge' => 'info',
                        'property custodian' => 'indigo',
                        'purchasing officer' => 'success',
                        'purchasing staff' => 'warning',
                        default => 'default',
                    };
                    @endphp
                    <x-badge :type="$badgeType">{{ $user->user_type ?? 'N/A' }}</x-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->status)
                        @php
                        $statusBadge = strtoupper($user->status) === 'ACTIVE' ? 'success' : 'danger';
                        @endphp
                        <x-badge :type="$statusBadge">{{ $user->status }}</x-badge>
                    @else
                        <span class="text-xs text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $user->email ?? '—' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ $user->username }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="#" data-url="{{ route('users.show', $user->id) }}" data-title="{{ $user->display_name }}" onclick="return openViewModal(this)" class="text-blue-600 hover:text-blue-800 p-1.5 rounded-lg hover:bg-blue-50 transition" title="View">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50 transition" title="Edit">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
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
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center text-gray-400">
                        <i class="fa-solid fa-users text-4xl mb-3"></i>
                        <p class="text-sm font-medium">No users found</p>
                        <p class="text-xs mt-1">Get started by creating a new user.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-slot>

        <x-slot name="pagination">
            {{ $users->appends(['search' => $search])->links() }}
        </x-slot>
    </x-data-table>
</div>
@endsection
