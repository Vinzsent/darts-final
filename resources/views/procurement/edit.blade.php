@extends('layouts.app')

@section('title', 'Edit Procurement - DARTS')
@section('page-title', 'Edit Procurement Record')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('procurement.update', $procurement->transaction_id) }}" method="POST" x-data="procurementForm()">
            @csrf
            @method('PUT')

            {{-- Supplier & Invoice --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" id="supplier_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('supplier_id') border-red-500 @enderror">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->supplier_id }}" {{ old('supplier_id', $procurement->supplier_id) == $s->supplier_id ? 'selected' : '' }}>
                                {{ $s->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="invoice_no" class="block text-sm font-medium text-gray-700 mb-1">Invoice No. <span class="text-red-500">*</span></label>
                    <input type="text" name="invoice_no" id="invoice_no" value="{{ old('invoice_no', $procurement->invoice_no) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('invoice_no') border-red-500 @enderror">
                    @error('invoice_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Dates & Type --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="date_received" class="block text-sm font-medium text-gray-700 mb-1">Date Received <span class="text-red-500">*</span></label>
                    <input type="date" name="date_received" id="date_received" value="{{ old('date_received', $procurement->date_received) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('date_received') border-red-500 @enderror">
                    @error('date_received') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="sales_type" class="block text-sm font-medium text-gray-700 mb-1">Sales Type</label>
                    <input type="text" name="sales_type" id="sales_type" value="{{ old('sales_type', $procurement->sales_type) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $procurement->category) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Item Description --}}
            <div class="mb-6">
                <label for="item_description" class="block text-sm font-medium text-gray-700 mb-1">Item Description <span class="text-red-500">*</span></label>
                <textarea name="item_description" id="item_description" rows="2" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('item_description') border-red-500 @enderror">{{ old('item_description', $procurement->item_description) }}</textarea>
                @error('item_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Item Details --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand', $procurement->brand) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $procurement->type) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color', $procurement->color) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Quantity & Pricing --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $procurement->quantity) }}" required min="1" x-model="quantity"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('quantity') border-red-500 @enderror">
                    @error('quantity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" id="unit" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('unit') border-red-500 @enderror">
                        <option value="">-- Select --</option>
                        @foreach(['pcs' => 'Pieces (pcs)', 'box' => 'Box', 'kg' => 'Kilogram (kg)', 'meter' => 'Meter', 'liter' => 'Liter', 'set' => 'Set', 'pack' => 'Pack', 'roll' => 'Roll'] as $val => $label)
                            <option value="{{ $val }}" {{ old('unit', $procurement->unit) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="unit_price" class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">&pound;</span>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" value="{{ old('unit_price', $procurement->unit_price) }}" required min="0" x-model="unitPrice"
                               class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('unit_price') border-red-500 @enderror">
                    </div>
                    @error('unit_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">&pound;</span>
                        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $procurement->amount) }}" min="0"
                               x-bind:value="quantity && unitPrice ? (quantity * unitPrice).toFixed(2) : ''"
                               class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Auto-calculated from Qty &times; Unit Price</p>
                </div>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="Pending" {{ old('status', $procurement->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Received" {{ old('status', $procurement->status) === 'Received' ? 'selected' : '' }}>Received</option>
                    <option value="Cancelled" {{ old('status', $procurement->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('procurement.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    <i class="fa-solid fa-save mr-2"></i> Update Procurement
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function procurementForm() {
        return {
            quantity: {{ old('quantity', $procurement->quantity) }},
            unitPrice: {{ old('unit_price', $procurement->unit_price) }},
        };
    }
</script>
@endpush
@endsection
