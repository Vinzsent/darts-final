<form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Brand <span class="text-red-500">*</span></label>
            <input type="text" name="brand" value="{{ old('brand', $item?->brand ?? '') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            @error('brand')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
            <input type="text" name="model" value="{{ old('model', $item?->model ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
            <input type="text" name="capacity" value="{{ old('capacity', $item?->capacity ?? '') }}" placeholder="1.5 HP" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Item No.</label>
            <input type="text" name="item_number" value="{{ old('item_number', $item?->item_number ?? '') }}" placeholder="AC-001" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Serial Number</label>
            <input type="text" name="serial_number" value="{{ old('serial_number', $item?->serial_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <input type="text" name="type" value="{{ old('type', $item?->type ?? '') }}" placeholder="Split, Window..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $item?->category ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                @php($cur = old('status', $item?->status ?? 'Working'))
                <option value="Working" {{ $cur == 'Working' ? 'selected' : '' }}>Working</option>
                <option value="Under Repair" {{ $cur == 'Under Repair' ? 'selected' : '' }}>Under Repair</option>
                <option value="Under Maintenance" {{ $cur == 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                <option value="Defective" {{ $cur == 'Defective' ? 'selected' : '' }}>Defective</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Schedule</label>
            <input type="text" name="maintenance_schedule" value="{{ old('maintenance_schedule', $item?->maintenance_schedule ?? '') }}" placeholder="Quarterly" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
    </div>

    <hr class="border-gray-200">
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location', $item?->location ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Area</label>
            <input type="text" name="area" value="{{ old('area', $item?->area ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Building</label>
            <input type="text" name="bldg" value="{{ old('bldg', $item?->bldg ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Campus</label>
            <input type="text" name="campus" value="{{ old('campus', $item?->campus ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Receiver</label>
            <input type="text" name="receiver" value="{{ old('receiver', $item?->receiver ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
            <select name="supplier_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">-- Select Supplier --</option>
                @foreach($suppliers as $supplier)
                    @php($sel = old('supplier_id', $item?->supplier_id ?? '') == $supplier->supplier_id ? 'selected' : '')
                    <option value="{{ $supplier->supplier_id }}" {{ $sel }}>{{ $supplier->supplier_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <hr class="border-gray-200">

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Installation Date</label>
            <input type="date" name="installation_date" value="{{ old('installation_date', $item?->installation_date ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Warranty Expiry</label>
            <input type="date" name="warranty_expiry" value="{{ old('warranty_expiry', $item?->warranty_expiry ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Date</label>
            <input type="date" name="purchase_date" value="{{ old('purchase_date', $item?->purchase_date ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Price</label>
            <input type="number" name="purchase_price" value="{{ old('purchase_price', $item?->purchase_price ?? '') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Power Consumption (kW)</label>
            <input type="number" name="power_consumption" value="{{ old('power_consumption', $item?->power_consumption ?? '') }}" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Energy Efficiency Rating</label>
            <input type="text" name="energy_efficiency_rating" value="{{ old('energy_efficiency_rating', $item?->energy_efficiency_rating ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">{{ old('notes', $item?->notes ?? '') }}</textarea>
    </div>
<div class="border-2 border-dashed border-gray-300 rounded-xl p-6">
        <h4 class="text-sm font-semibold text-gray-900 flex items-center"><i class="fa-solid fa-image text-emerald-600 mr-2"></i> Unit Photos
            <span class="ml-2 text-xs text-gray-400">(max 5 — {{ $item->images->count() ?? 0 }} stored)</span>
        </h4>

        @if($item && !$item->images->isEmpty())
            <div class="mt-3 grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach($item->images as $img)
                    <label class="relative rounded-lg overflow-hidden border border-gray-200 aspect-square cursor-pointer group">
                        <img src="{{ $img->url ?? '' }}" class="w-full h-full object-cover" onerror="this.style.display='none';">
                        <input type="checkbox" name="remove_images[]" value="{{ $img->id }}" class="absolute top-1 right-1 w-4 h-4 accent-red-600">
                        <span class="absolute inset-x-0 bottom-0 bg-red-600 text-white text-[10px] text-center py-0.5 opacity-0 group-hover:opacity-100 transition">Remove</span>
                    </label>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 mt-3">No photos yet. Upload below (up to 5 in total).</p>
        @endif

        <input type="file" name="images[]" id="airconImages" accept="image/*" multiple class="mt-3 block w-full text-sm text-gray-600 cursor-pointer">
        <div id="imagePreview" class="mt-4 grid grid-cols-2 sm:grid-cols-8 gap-3"></div>
    </div>

    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
        <a href="{{ route('aircon.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Cancel</a>
        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">
            <i class="fa-solid fa-floppy-disk mr-2"></i> {{ $submitLabel }}
        </button>
    </div>
</form>

<script>
    (function () {
        const input = document.getElementById('airconImages');
        const preview = document.getElementById('imagePreview');
        const MAX = 5;
        if (!input) return;
        input.addEventListener('change', function () {
            preview.innerHTML = '';
            const list = Array.from(input.files).slice(0, MAX);
            list.forEach((file) => {
                if (!file.type.startsWith('image/')) return;
                const url = URL.createObjectURL(file);
                const wrap = document.createElement('div');
                wrap.className = 'relative rounded-lg overflow-hidden border border-gray-200 aspect-square';
                wrap.innerHTML = '<img src="' + url + '" class="w-full h-full object-cover">';
                preview.appendChild(wrap);
            });
            if (input.files.length > MAX) {
                const note = document.createElement('p');
                note.className = 'text-xs text-amber-600 mt-2';
                note.textContent = 'Only the first ' + MAX + ' photos will be saved.';
                preview.appendChild(note);
            }
        });
    })();
</script>