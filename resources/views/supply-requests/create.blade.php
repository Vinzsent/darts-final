@extends('layouts.app')

@section('title', 'New Supply Request - DARTS')
@section('page-title', 'New Supply Request')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ route('supply-requests.index') }}" class="hover:text-emerald-600">Supply Requests</a>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="text-gray-900">New Request</span>
            </div>
        </div>

        <form method="POST" action="{{ route('supply-requests.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Request Information --}}
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-circle-info text-emerald-600 mr-2"></i>Request Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department / Unit <span class="text-red-500">*</span></label>
                        <select name="department_unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('department_unit') border-red-500 @enderror">
                            <option value="">Select Department</option>
                            @foreach(['Academic', 'Administration', 'Finance', 'HR', 'IT', 'Logistics', 'Supply Office'] as $dept)
                                <option value="{{ $dept }}" {{ old('department_unit') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                            @endforeach
                        </select>
                        @error('department_unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Needed <span class="text-red-500">*</span></label>
                        <input type="date" name="date_needed" value="{{ old('date_needed') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('date_needed') border-red-500 @enderror">
                        @error('date_needed') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose <span class="text-red-500">*</span></label>
                        <select name="purpose" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('purpose') border-red-500 @enderror">
                            <option value="">Select Purpose</option>
                            @foreach(['Classroom Use', 'Office Use', 'Event', 'Repair & Maintenance', 'Stock Replenishment', 'Other'] as $opt)
                                <option value="{{ $opt }}" {{ old('purpose') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                        @error('purpose') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sales Type</label>
                        <select name="sales_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Type</option>
                            <option value="Direct" {{ old('sales_type') == 'Direct' ? 'selected' : '' }}>Direct</option>
                            <option value="Consignment" {{ old('sales_type') == 'Consignment' ? 'selected' : '' }}>Consignment</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Request Type</label>
                        <select name="request_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Type</option>
                            <option value="New" {{ old('request_type') == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Replacement" {{ old('request_type') == 'Replacement' ? 'selected' : '' }}>Replacement</option>
                            <option value="Additional" {{ old('request_type') == 'Additional' ? 'selected' : '' }}>Additional</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Item Details --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-box text-emerald-600 mr-2"></i>Item Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                        <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            @foreach(['Office Supplies', 'School Supplies', 'Furniture', 'Equipment', 'IT Equipment', 'Cleaning Materials', 'Tools', 'Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                        <input type="text" name="item_name" value="{{ old('item_name') }}" placeholder="Enter item name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('item_name') border-red-500 @enderror">
                        @error('item_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="request_description" rows="2" placeholder="Optional description"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('request_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Brand"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}" placeholder="Color"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                        <input type="text" name="size" value="{{ old('size') }}" placeholder="Size"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <input type="text" name="type" value="{{ old('type') }}" placeholder="Type"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity_requested" value="{{ old('quantity_requested') }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('quantity_requested') border-red-500 @enderror">
                        @error('quantity_requested') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                        <select name="unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('unit') border-red-500 @enderror">
                            <option value="">Select Unit</option>
                            @foreach(['pcs', 'box', 'pack', 'ream', 'set', 'unit', 'liter', 'kg', 'meter', 'pair', 'bottle', 'roll'] as $u)
                                <option value="{{ $u }}" {{ old('unit') == $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                        @error('unit') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">PHP</span>
                            <input type="number" step="0.01" name="unit_cost" value="{{ old('unit_cost') }}" min="0"
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Cost</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">PHP</span>
                            <input type="number" step="0.01" name="total_cost" value="{{ old('total_cost') }}" min="0"
                                   class="w-full pl-12 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Semester & School Year --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fa-solid fa-calendar text-emerald-600 mr-2"></i>Academic Period
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                        <select name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select Semester</option>
                            <option value="1st Semester" {{ old('semester') == '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                            <option value="2nd Semester" {{ old('semester') == '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                            <option value="Summer" {{ old('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">School Year</label>
                        <input type="text" name="school_year" value="{{ old('school_year', date('Y') . '-' . (date('Y') + 1)) }}"
                               placeholder="e.g. 2025-2026"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="border-t border-gray-100 pt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('supply-requests.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-paper-plane mr-2"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
