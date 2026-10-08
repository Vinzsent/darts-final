@extends('layouts.app')

@section('title', 'Edit Property Request - DARTS')
@section('page-title', 'Edit Property Request')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('property-requests.index') }}" class="hover:text-emerald-600">Property Requests</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-900">Edit Request</span>
            </div>
        </div>

        <form method="POST" action="{{ route('property-requests.update', $propertyRequest->property_id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Scan Request QR --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-900 flex items-center">
                    <i class="fa-solid fa-qrcode text-emerald-600 mr-2"></i> Scan Request QR Code
                </h3>
                <p class="text-xs text-gray-500 mt-1">Scan or paste the employee's request QR code to auto-fill this form.</p>
                <div id="qrReader" class="hidden mt-3 rounded-lg overflow-hidden border border-emerald-200 max-w-xs"></div>
                <div class="mt-3 flex flex-col sm:flex-row gap-2">
                    <button type="button" id="qrScanBtn" class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                        <i class="fa-solid fa-camera mr-2"></i> Scan with Camera
                    </button>
                    <input type="text" id="qrManual" placeholder="...or paste QR code / SKU here" class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                    <button type="button" id="qrApplyBtn" class="inline-flex items-center justify-center px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        <i class="fa-solid fa-check mr-2"></i> Apply
                    </button>
                </div>
                <p id="qrStatus" class="mt-2 text-xs text-gray-500 hidden"></p>
                <input type="hidden" name="qrcode" id="qrcodeInput" value="{{ old('qrcode', $propertyRequest->qrcode) }}">
            </div>

            {{-- Department --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Department Unit <span class="text-red-500">*</span></label>
                <select name="department_unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('department_unit') border-red-500 @enderror" required>
                    <option value="">Select department...</option>
                    @foreach(['Academic', 'Administration', 'Finance', 'HR', 'IT', 'Logistics'] as $dept)
                        <option value="{{ $dept }}" {{ old('department_unit', $propertyRequest->department_unit) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
                @error('department_unit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Transfer Type --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Temporary Transfer</label>
                    <select name="temporary_transfer" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(['Yes', 'No'] as $opt)
                            <option value="{{ $opt }}" {{ old('temporary_transfer', $propertyRequest->temporary_transfer) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Permanent Transfer</label>
                    <select name="permanent_transfer" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @foreach(['Yes', 'No'] as $opt)
                            <option value="{{ $opt }}" {{ old('permanent_transfer', $propertyRequest->permanent_transfer) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Return <span class="text-gray-400 text-xs">(for temporary transfer)</span></label>
                    <input type="date" name="date_return" value="{{ old('date_return', $propertyRequest->date_return) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('date_return') border-red-500 @enderror">
                    @error('date_return')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Request Type</label>
                    <input type="text" name="request_type" value="{{ old('request_type', $propertyRequest->request_type) }}" placeholder="e.g. Transfer, Borrow"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Reason --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Transfer <span class="text-red-500">*</span></label>
                <textarea name="reason_for_transfer" rows="2" placeholder="State the reason for this request"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('reason_for_transfer') border-red-500 @enderror" required>{{ old('reason_for_transfer', $propertyRequest->reason_for_transfer) }}</textarea>
                @error('reason_for_transfer')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Item Details --}}
            <div class="border-t border-gray-100 pt-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Item Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                        <input type="text" name="item_name" value="{{ old('item_name', $propertyRequest->item_name) }}" placeholder="Enter item name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('item_name') border-red-500 @enderror" required>
                        @error('item_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <input type="text" name="category" value="{{ old('category', $propertyRequest->category) }}" placeholder="e.g. Furniture, Equipment"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror" required>
                        @error('category')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand', $propertyRequest->brand) }}" placeholder="Brand"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" name="color" value="{{ old('color', $propertyRequest->color) }}" placeholder="Color"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <input type="text" name="type" value="{{ old('type', $propertyRequest->type) }}" placeholder="Type"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity Requested <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity_requested" value="{{ old('quantity_requested', $propertyRequest->quantity_requested) }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('quantity_requested') border-red-500 @enderror" required>
                        @error('quantity_requested')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tagging</label>
                        <input type="text" name="tagging" value="{{ old('tagging', $propertyRequest->tagging) }}" placeholder="e.g. PPE, Semi-expendable"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="request_description" rows="2" placeholder="Optional description"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('request_description', $propertyRequest->request_description) }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                <a href="{{ route('property-requests.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
<x-qr-autofill />
<x-item-autocomplete />
@endsection
