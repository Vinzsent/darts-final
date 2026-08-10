@props(['title', 'value', 'icon' => 'fa-chart-bar', 'color' => 'emerald'])

@php
$colors = [
    'emerald' => 'bg-emerald-50 text-emerald-600',
    'blue' => 'bg-blue-50 text-blue-600',
    'amber' => 'bg-amber-50 text-amber-600',
    'rose' => 'bg-rose-50 text-rose-600',
    'indigo' => 'bg-indigo-50 text-indigo-600',
];
$bgColors = [
    'emerald' => 'bg-emerald-50',
    'blue' => 'bg-blue-50',
    'amber' => 'bg-amber-50',
    'rose' => 'bg-rose-50',
    'indigo' => 'bg-indigo-50',
];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ $bgColors[$color] }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 rounded-lg {{ $colors[$color] }} flex items-center justify-center">
            <i class="fa-solid {{ $icon }} text-xl"></i>
        </div>
    </div>
</div>
