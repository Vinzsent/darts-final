@extends('layouts.app')

@section('title', 'Add Property Item - DARTS')
@section('page-title', 'Add Property Item')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-couch text-emerald-600"></i>
                <h3 class="text-lg font-semibold text-gray-900">Property Information</h3>
            </div>
        </div>

        <form action="{{ route('property.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            {{-- Row: Item Name + Category --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('item_name') border-red-500 @enderror">
                    @error('item_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row: Brand + Type + Color + Size --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <input type="text" name="type" value="{{ old('type') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" value="{{ old('color') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                    <input type="text" name="size" value="{{ old('size') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description') }}</textarea>
            </div>

            {{-- Barcode --}}
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-1">
                    <label class="block text-sm font-medium text-gray-700" for="barcode">Barcode</label>
                    <div class="inline-flex rounded-lg border border-gray-300 overflow-hidden text-xs font-semibold" role="group">
                        <button type="button" id="barcodeAutoBtn" onclick="setBarcodeMode('auto')"
                                class="px-3 py-1.5 bg-emerald-600 text-white transition focus:outline-none">
                            <i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Automatic
                        </button>
                        <button type="button" id="barcodeManualBtn" onclick="setBarcodeMode('manual')"
                                class="px-3 py-1.5 bg-white text-gray-600 hover:bg-gray-50 transition focus:outline-none">
                            <i class="fa-solid fa-keyboard mr-1"></i>Manual
                        </button>
                    </div>
                </div>
                <input type="hidden" name="barcode_mode" id="barcodeMode" value="auto">
                <input type="text" name="barcode" id="barcodeInput" value="{{ old('barcode') }}"
                       placeholder="Auto-generated on save (e.g., PRP-20260827-XXXX)"
                       disabled
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-100 text-gray-500 cursor-not-allowed font-mono focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('barcode') border-red-500 @enderror">
                <p id="barcodeHint" class="text-xs text-gray-400 mt-1">System will generate a unique barcode automatically.</p>
                @error('barcode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-200">

            {{-- Row: Stock + Unit + Cost + Reorder --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" min="0" step="any"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', 0) }}" min="0" step="any"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" placeholder="pcs, kg, box..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                    <div class="relative">
                        <input type="number" name="unit_cost" value="{{ old('unit_cost') }}" min="0" step="0.01"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            {{-- Row: Supplier + Location + Receiver --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Select Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receiver</label>
                    <input type="text" name="receiver" value="{{ old('receiver') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Row: Status + Reorder Level --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Discontinued" {{ old('status') == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                    <input type="number" name="reorder_level" value="{{ old('reorder_level', 0) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Received Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="received_notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('received_notes') }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('property.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save Property
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function setBarcodeMode(mode) {
        const input = document.getElementById('barcodeInput');
        const modeInput = document.getElementById('barcodeMode');
        const hint = document.getElementById('barcodeHint');
        const autoBtn = document.getElementById('barcodeAutoBtn');
        const manualBtn = document.getElementById('barcodeManualBtn');
        if (!input || !modeInput) return;

        modeInput.value = mode;
        const isAuto = mode === 'auto';

        input.disabled = isAuto;
        input.classList.toggle('bg-gray-100', isAuto);
        input.classList.toggle('text-gray-500', isAuto);
        input.classList.toggle('cursor-not-allowed', isAuto);
        if (isAuto) { input.value = ''; }

        autoBtn.classList.toggle('bg-emerald-600', isAuto);
        autoBtn.classList.toggle('text-white', isAuto);
        autoBtn.classList.toggle('bg-white', !isAuto);
        autoBtn.classList.toggle('text-gray-600', !isAuto);

        manualBtn.classList.toggle('bg-emerald-600', !isAuto);
        manualBtn.classList.toggle('text-white', !isAuto);
        manualBtn.classList.toggle('bg-white', isAuto);
        manualBtn.classList.toggle('text-gray-600', isAuto);

        hint.textContent = isAuto
            ? 'System will generate a unique barcode automatically.'
            : 'Enter the barcode manually. It must be unique.';
        hint.classList.toggle('text-gray-400', isAuto);
        hint.classList.toggle('text-emerald-600', !isAuto);
        if (!isAuto) input.focus();
    }
</script>
@endpush
@endsection