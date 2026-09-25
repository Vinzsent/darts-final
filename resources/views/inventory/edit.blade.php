@extends('layouts.app')

@section('title', 'Edit Inventory Item - DARTS')
@section('page-title', 'Edit Inventory Item')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-pen-to-square text-amber-600"></i>
                <h3 class="text-lg font-semibold text-gray-900">Edit: {{ $item->item_name }}</h3>
            </div>
        </div>

        <form action="{{ route('inventory.update', $item->inventory_id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Row: Item Name + Category --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                    <input type="text" name="item_name" value="{{ old('item_name', $item->item_name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('item_name') border-red-500 @enderror">
                    @error('item_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category', $item->category) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 @error('category') border-red-500 @enderror">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row: Brand + Type + Color + Size --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $item->brand) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <input type="text" name="type" value="{{ old('type', $item->type) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <input type="text" name="color" value="{{ old('color', $item->color) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Size</label>
                    <input type="text" name="size" value="{{ old('size', $item->size) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description', $item->description) }}</textarea>
            </div>

            <hr class="border-gray-200">

            {{-- Row: Stock + Unit + Cost + Reorder --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock</label>
                    <input type="number" name="current_stock" value="{{ old('current_stock', $item->current_stock) }}" min="0" step="any"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="0" step="any"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" placeholder="pcs, kg, box..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">&#8369;</span>
                        <input type="number" name="unit_cost" value="{{ old('unit_cost', $item->unit_cost) }}" min="0" step="0.01"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
            </div>

            {{-- Row: Supplier + Location + Receiver --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div x-data="{
                    open: false,
                    search: '',
                    selectedId: '{{ old('supplier_id', $item->supplier_id) }}',
                    selectedName: '{{ addslashes($suppliers->firstWhere('supplier_id', old('supplier_id', $item->supplier_id))?->supplier_name ?? '') }}',
                    suppliers: {{ json_encode($suppliers->map(fn($s) => ['id' => (string) $s->supplier_id, 'name' => $s->supplier_name])) }},
                    get filtered() {
                        if (!this.search.trim()) return this.suppliers;
                        const q = this.search.toLowerCase();
                        return this.suppliers.filter(s => s.name.toLowerCase().includes(q));
                    },
                    select(s) {
                        this.selectedId = s ? s.id : '';
                        this.selectedName = s ? s.name : '';
                        this.search = '';
                        this.open = false;
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input type="hidden" name="supplier_id" :value="selectedId">
                    
                    <div class="relative">
                        <input type="text"
                               x-model="search"
                               @focus="open = true"
                               @click.outside="open = false; search = ''"
                               @keydown.escape="open = false; search = ''"
                               :placeholder="selectedName || '-- Search / Enter Supplier Name or Number --'"
                               class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                        <button type="button"
                                @click="open = !open"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-1">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                    </div>

                    <div x-show="open"
                         x-transition
                         class="absolute z-30 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                         style="display: none;">
                        <div @click="select(null)"
                             class="px-3 py-2 text-xs text-gray-500 hover:bg-gray-100 cursor-pointer border-b border-gray-100 flex items-center justify-between">
                            <span>-- No Supplier / Clear --</span>
                        </div>
                        <template x-for="s in filtered" :key="s.id">
                            <div @click="select(s)"
                                 :class="{ 'bg-emerald-50 font-semibold text-emerald-800': selectedId == s.id }"
                                 class="px-3 py-2 text-sm text-gray-800 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer flex items-center justify-between border-b border-gray-50 last:border-0">
                                <span x-text="s.name"></span>
                                <span x-show="selectedId == s.id" class="text-xs text-emerald-600"><i class="fa-solid fa-check"></i></span>
                            </div>
                        </template>
                        <div x-show="filtered.length === 0" class="px-3 py-3 text-sm text-gray-400 text-center">
                            No supplier found matching your search.
                        </div>
                    </div>

                    <div x-show="selectedName" class="mt-1 flex items-center justify-between text-xs text-gray-500">
                        <span class="truncate">Selected: <strong class="text-emerald-700 font-medium" x-text="selectedName"></strong></span>
                        <button type="button" @click="select(null)" class="text-red-500 hover:text-red-700 ml-2 shrink-0">Clear</button>
                    </div>
                    @error('supplier_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $item->location) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receiver</label>
                    <input type="text" name="receiver" value="{{ old('receiver', $item->receiver) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Row: Status + Reorder Level --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="Active" {{ old('status', $item->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status', $item->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Discontinued" {{ old('status', $item->status) == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                    <input type="number" name="reorder_level" value="{{ old('reorder_level', $item->reorder_level) }}" min="0"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>

            {{-- Received Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Received Notes</label>
                <textarea name="received_notes" rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('received_notes', $item->received_notes) }}</textarea>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Update Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
