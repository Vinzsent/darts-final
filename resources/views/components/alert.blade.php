@props(['type' => 'info', 'message' => ''])

@php
$colors = [
    'success' => 'bg-green-50 border-green-500 text-green-700',
    'error' => 'bg-red-50 border-red-500 text-red-700',
    'warning' => 'bg-yellow-50 border-yellow-500 text-yellow-700',
    'info' => 'bg-blue-50 border-blue-500 text-blue-700',
];
$icons = [
    'success' => 'fa-circle-check',
    'error' => 'fa-circle-xmark',
    'warning' => 'fa-triangle-exclamation',
    'info' => 'fa-circle-info',
];
@endphp

@if($message)
    <div class="mb-4 border-l-4 rounded-r-lg p-4 shadow-sm {{ $colors[$type] }}" role="alert">
        <div class="flex items-center">
            <i class="fa-solid {{ $icons[$type] }} mr-2"></i>
            <p class="text-sm font-medium">{{ $message }}</p>
        </div>
    </div>
@endif

{{ $slot ?? '' }}
