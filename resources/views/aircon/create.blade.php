@extends('layouts.app')

@section('title', 'Add Aircon - DARTS')
@section('page-title', 'Add Aircon')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center space-x-2">
            <i class="fa-solid fa-fan text-emerald-600"></i>
            <h3 class="text-lg font-semibold text-gray-900">Aircon Unit Information</h3>
        </div>

        @include('aircon._form', [
            'formAction' => route('aircon.store'),
            'isEdit' => false,
            'item' => null,
            'suppliers' => $suppliers,
            'submitLabel' => 'Save Aircon',
        ])
    </div>
</div>
@endsection