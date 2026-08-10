@extends(request()->has('modal') ? 'layouts.blank' : 'layouts.app')

@section('title', 'Procurement Details - DARTS')
@section('page-title', 'Procurement Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Action Bar --}}
    @if(!request()->has('modal'))
    <div class="flex items-center justify-between">
        <a href="{{ route('procurement.index') }}" class="inline-flex items-center px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
        <div class="flex items-center gap-2">
            @if($procurement->status !== 'Received')
            <form action="{{ route('procurement.receive', $procurement->transaction_id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition"
                        onclick="return confirm('Mark this procurement as received?')">
                    <i class="fa-solid fa-check-circle mr-2"></i> Mark as Received
                </button>
            </form>
            @endif
            <a href="{{ route('procurement.edit', $procurement->transaction_id) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
            </a>
        </div>
    </div>
    @endif

    {{-- Main Details Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Procurement Information</h3>
            @php
                $statusClass = match($procurement->status) {
                    'Received' => 'success',
                    'Pending' => 'warning',
                    'Cancelled' => 'danger',
                    default => 'default',
                };
            @endphp
            <x-badge :type="$statusClass">{{ $procurement->status ?? '--' }}</x-badge>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Invoice Information</h4>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Invoice No.</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->invoice_no }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Date Received</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->date_received ? date('M d, Y', strtotime($procurement->date_received)) : '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Sales Type</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->sales_type ?? '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Category</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->category ?? '--' }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Supplier</h4>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Supplier</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->supplier->supplier_name ?? '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Contact</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->supplier->contact_person ?? '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Contact #</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->supplier->contact_number ?? '--' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Item Details Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Item Details</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Item Description</dt>
                            <dd class="text-sm font-medium text-gray-900 text-right max-w-xs">{{ $procurement->item_description }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Brand</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->brand ?? '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Type</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->type ?? '--' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Color</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->color ?? '--' }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Quantity</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $procurement->quantity }} {{ $procurement->unit }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Unit Price</dt>
                            <dd class="text-sm font-medium text-gray-900">&pound;{{ number_format($procurement->unit_price, 2) }}</dd>
                        </div>
                        <div class="flex justify-between border-t border-gray-100 pt-3">
                            <dt class="text-sm font-semibold text-gray-700">Total Amount</dt>
                            <dd class="text-sm font-bold text-gray-900">&pound;{{ number_format($procurement->amount, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Timeline Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Status Information</h3>
        </div>
        <div class="p-6">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full {{ $procurement->status === 'Pending' || $procurement->status === 'Received' ? 'bg-amber-500' : 'bg-gray-300' }}"></div>
                    <span class="text-sm {{ $procurement->status === 'Pending' || $procurement->status === 'Received' ? 'text-gray-900 font-medium' : 'text-gray-400' }}">Pending</span>
                </div>
                <div class="flex-1 h-px {{ $procurement->status === 'Received' ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full {{ $procurement->status === 'Received' ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                    <span class="text-sm {{ $procurement->status === 'Received' ? 'text-gray-900 font-medium' : 'text-gray-400' }}">Received</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
