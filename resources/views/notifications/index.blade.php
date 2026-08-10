@extends('layouts.app')

@section('title', 'Notifications - DARTS')
@section('page-title', 'Notifications')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-sm text-gray-500">Your system notifications.</p>
        <form action="{{ route('notifications.read-all') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                <i class="fa-solid fa-check-double mr-1"></i> Mark All as Read
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @forelse($notifications as $notification)
        <div class="px-6 py-4 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-blue-50/50' : '' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $notification->title ?? 'Notification' }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at }}</p>
                </div>
                @if(!$notification->is_read)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ml-4">
                    @csrf
                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-700">
                        <i class="fa-solid fa-circle"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12 text-gray-500">
            <i class="fa-solid fa-bell-slash text-3xl mb-2"></i>
            <p>No notifications yet.</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="px-6 py-4">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
