@extends('layouts.app')

@section('title', 'Edit Aircon - DARTS')
@section('page-title', 'Edit Aircon')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center space-x-2">
            <i class="fa-solid fa-pen-to-square text-amber-600"></i>
            <h3 class="text-lg font-semibold text-gray-900">Edit: {{ $item->brand }} {{ $item->model }}</h3>
        </div>

        @include('aircon._form', [
            'formAction' => route('aircon.update', $item->aircon_id),
            'isEdit' => true,
            'item' => $item,
            'suppliers' => $suppliers,
            'submitLabel' => 'Update Aircon',
        ])
    </div>
</div>
@endsection