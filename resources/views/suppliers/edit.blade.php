@extends('layouts.app')

@section('title', 'Edit Supplier - DARTS')
@section('page-title', 'Edit Supplier')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Edit Supplier</h2>
                <a href="{{ route('suppliers.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back to Suppliers
                </a>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('suppliers.update', $supplier->supplier_id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div>
                <h3 class="text-base font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Supplier Name --}}
                    <div class="md:col-span-2">
                        <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Supplier Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="supplier_name" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('supplier_name') border-red-500 @enderror"
                               placeholder="Enter supplier name">
                        @error('supplier_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contact Person --}}
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Full name">
                    </div>

                    {{-- Business Type --}}
                    <div>
                        <label for="business_type" class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                        <input type="text" id="business_type" name="business_type" value="{{ old('business_type', $supplier->business_type) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="e.g. Distributor, Manufacturer">
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" id="category" name="category" value="{{ old('category', $supplier->category) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="e.g. Raw Materials, Office Supplies">
                    </div>

                    {{-- Payment Terms --}}
                    <div>
                        <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-1">Payment Terms</label>
                        <input type="text" id="payment_terms" name="payment_terms" value="{{ old('payment_terms', $supplier->payment_terms) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="e.g. Net 30, COD">
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-medium text-gray-900 mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Contact Number --}}
                    <div>
                        <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                        <input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number', $supplier->contact_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="+63 XXX XXX XXXX">
                    </div>

                    {{-- Landline Number --}}
                    <div>
                        <label for="landline_number" class="block text-sm font-medium text-gray-700 mb-1">Landline Number</label>
                        <input type="text" id="landline_number" name="landline_number" value="{{ old('landline_number', $supplier->landline_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="(XXX) XXX-XXXX">
                    </div>

                    {{-- Email Address --}}
                    <div>
                        <label for="email_address" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="email_address" name="email_address" value="{{ old('email_address', $supplier->email_address) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('email_address') border-red-500 @enderror"
                               placeholder="supplier@example.com">
                        @error('email_address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fax Number --}}
                    <div>
                        <label for="fax_number" class="block text-sm font-medium text-gray-700 mb-1">Fax Number</label>
                        <input type="text" id="fax_number" name="fax_number" value="{{ old('fax_number', $supplier->fax_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Fax number">
                    </div>

                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" id="website" name="website" value="{{ old('website', $supplier->website) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="https://example.com">
                    </div>

                    {{-- Tax Identification Number --}}
                    <div>
                        <label for="tax_identification_number" class="block text-sm font-medium text-gray-700 mb-1">Tax ID (TIN)</label>
                        <input type="text" id="tax_identification_number" name="tax_identification_number" value="{{ old('tax_identification_number', $supplier->tax_identification_number) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="XXX-XXX-XXX-XXX">
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-medium text-gray-900 mb-4">Address</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Street Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $supplier->address) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Building, street, barangay">
                    </div>

                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $supplier->city) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="City">
                    </div>

                    {{-- Province --}}
                    <div>
                        <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                        <input type="text" id="province" name="province" value="{{ old('province', $supplier->province) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Province">
                    </div>

                    {{-- Zip Code --}}
                    <div>
                        <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', $supplier->zip_code) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="XXXX">
                    </div>

                    {{-- Country --}}
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', $supplier->country) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                               placeholder="Country">
                    </div>
                </div>
            </div>

            {{-- Status & Notes --}}
            <div class="pt-4 border-t border-gray-200">
                <h3 class="text-base font-medium text-gray-900 mb-4">Status & Notes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="ACTIVE" {{ old('status', $supplier->status) == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                            <option value="INACTIVE" {{ old('status', $supplier->status) == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                            <option value="PENDING" {{ old('status', $supplier->status) == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                  placeholder="Additional notes...">{{ old('notes', $supplier->notes) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('suppliers.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-save mr-2"></i> Update Supplier
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
