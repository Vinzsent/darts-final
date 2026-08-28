@extends('layouts.app')

@section('title', 'Create Canvass - DARTS')
@section('page-title', 'Create Canvass')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-600">Canvass</p>
            <h2 class="mt-1 text-2xl font-bold text-slate-900">Create New Canvass</h2>
        </div>
        <a href="{{ route('canvass.index') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Back to list
        </a>
    </div>

    <form action="{{ route('canvass.store') }}" method="POST" class="space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        @csrf

        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Canvass Date <span class="text-red-500">*</span></label>
                <input type="date" name="canvass_date" value="{{ old('canvass_date', now()->format('Y-m-d')) }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                @error('canvass_date')
                    <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="Canvassed" {{ old('status') == 'Canvassed' ? 'selected' : '' }}>Canvassed</option>
                    <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Cancelled" {{ old('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <span class="mt-1 block text-xs text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Canvassed By</label>
                <select name="canvassed_by" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                    <option value="">-- Select --</option>
                    @foreach(
                        \App\Models\User::orderBy('first_name')->get() as $user
                    )
                        <option value="{{ $user->id }}" {{ old('canvassed_by') == $user->id ? 'selected' : '' }}>{{ $user->display_name }} ({{ $user->user_type }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
            <textarea name="notes" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">{{ old('notes') }}</textarea>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Canvass Items</h3>
                <button type="button" id="add-item" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add Item
                </button>
            </div>

            <div id="items-container" class="space-y-4"></div>

            <div class="mt-5 flex items-center justify-end gap-3 border-t border-slate-200 pt-4">
                <span class="text-sm font-medium text-slate-600">Total Amount:</span>
                <span id="total-amount" class="text-lg font-bold text-slate-900">₱0.00</span>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('canvass.index') }}" class="inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                <i class="fa-solid fa-floppy-disk mr-2"></i>
                Save Canvass
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemIndex = 0;

    function addItemRow() {
        const container = document.getElementById('items-container');
        const row = document.createElement('div');
        row.className = 'rounded-2xl border border-slate-200 bg-white p-4';
        row.dataset.index = itemIndex;

        row.innerHTML = `
            <div class="mb-3 flex items-center justify-between">
                <h4 class="text-sm font-semibold text-slate-900">Item #${itemIndex + 1}</h4>
                <button type="button" class="remove-item text-sm font-medium text-red-600 hover:text-red-700">
                    <i class="fa-solid fa-trash-can mr-1"></i> Remove
                </button>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Item Description <span class="text-red-500">*</span></label>
                    <textarea name="items[${itemIndex}][item_description]" rows="2" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Supplier Name <span class="text-red-500">*</span></label>
                    <input type="text" name="items[${itemIndex}][supplier_name]" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
                    <input type="text" name="items[${itemIndex}][department]" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Campus</label>
                    <input type="text" name="items[${itemIndex}][campus]" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemIndex}][quantity]" value="0" min="0" step="0.01" required class="item-qty w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Unit Cost <span class="text-red-500">*</span></label>
                    <input type="number" name="items[${itemIndex}][unit_cost]" value="0" min="0" step="0.01" required class="item-cost w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                </div>
            </div>
        `;

        container.appendChild(row);
        itemIndex++;
        updateTotalAmount();

        row.querySelector('.remove-item').addEventListener('click', function () {
            row.remove();
            updateTotalAmount();
        });

        row.querySelectorAll('.item-qty, .item-cost').forEach(function (input) {
            input.addEventListener('input', updateTotalAmount);
        });
    }

    function updateTotalAmount() {
        let total = 0;
        document.querySelectorAll('#items-container .item-qty').forEach(function (qtyInput) {
            const row = qtyInput.closest('[data-index]');
            const quantity = parseFloat(qtyInput.value) || 0;
            const costInput = row.querySelector('.item-cost');
            const cost = parseFloat(costInput.value) || 0;
            total += quantity * cost;
        });
        document.getElementById('total-amount').textContent = '₱' + total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    document.getElementById('add-item').addEventListener('click', addItemRow);
    addItemRow();
</script>
@endpush
@endsection
