@extends(request()->has('modal') ? 'layouts.blank' : 'layouts.app')

@section('title', $supplier->supplier_name . ' - DARTS')
@section('page-title', $supplier->supplier_name)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    @if(!request()->has('modal'))
    <div class="flex items-center justify-between">
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Suppliers
        </a>
        <div class="flex items-center space-x-2">
            <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition">
                <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
            </a>
            <form method="POST" action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" onsubmit="return confirm('Are you sure you want to delete this supplier?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition">
                    <i class="fa-solid fa-trash-can mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Supplier Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Supplier Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier Name</p>
                            <p class="mt-1 text-sm text-gray-900 font-medium">{{ $supplier->supplier_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                            <p class="mt-1">
                                @php
                                $badgeType = match(strtoupper($supplier->status ?? '')) {
                                    'ACTIVE' => 'success',
                                    'INACTIVE' => 'danger',
                                    'PENDING' => 'warning',
                                    default => 'default',
                                };
                                @endphp
                                <x-badge :type="$badgeType">{{ $supplier->status ?? 'N/A' }}</x-badge>
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Business Type</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->business_type ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->category ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Terms</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->payment_terms ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Tax ID (TIN)</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->tax_identification_number ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Contact Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->contact_person ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Mobile Number</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($supplier->contact_number)
                                    <a href="tel:{{ $supplier->contact_number }}" class="text-emerald-600 hover:text-emerald-800">{{ $supplier->contact_number }}</a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Landline Number</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->landline_number ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($supplier->email_address)
                                    <a href="mailto:{{ $supplier->email_address }}" class="text-emerald-600 hover:text-emerald-800">{{ $supplier->email_address }}</a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Fax Number</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $supplier->fax_number ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Website</p>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($supplier->website)
                                    <a href="{{ $supplier->website }}" target="_blank" rel="noopener noreferrer" class="text-emerald-600 hover:text-emerald-800">
                                        {{ $supplier->website }} <i class="fa-solid fa-external-link text-xs ml-1"></i>
                                    </a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Address --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Address</h3>
                </div>
                <div class="p-6">
                    @if($supplier->address || $supplier->city || $supplier->province)
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot text-gray-400 mt-0.5"></i>
                            <div>
                                <p class="text-sm text-gray-900">{{ $supplier->full_address }}</p>
                                @if($supplier->country)
                                    <p class="text-sm text-gray-500 mt-1">{{ $supplier->country }}</p>
                                @endif
                                @if($supplier->zip_code)
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $supplier->zip_code }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">No address provided.</p>
                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($supplier->notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $supplier->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar Stats --}}
        <div class="space-y-6">
            {{-- Stats Cards --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Overview</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Procurements</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['total_procurements'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Transactions</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['total_transactions'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Inventory Items</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $stats['total_inventory_items'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Pending Procurements</span>
                        <span class="text-sm font-semibold text-amber-600">{{ $stats['pending_procurements'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Procurement Transactions Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900">Procurement Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice No.</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Received</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($supplier->procurements as $procurement)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $procurement->invoice_no ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $procurement->date_received ? date('M d, Y', strtotime($procurement->date_received)) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $procurement->item_description ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $procurement->quantity ?? '—' }} {{ $procurement->unit ?? '' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            @if($procurement->amount)
                                &#8369;{{ number_format($procurement->amount, 2) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $pBadge = match(strtoupper($procurement->status ?? '')) {
                                'COMPLETED' => 'success',
                                'PENDING' => 'warning',
                                'CANCELLED' => 'danger',
                                default => 'default',
                            };
                            @endphp
                            <x-badge :type="$pBadge">{{ $procurement->status ?? 'N/A' }}</x-badge>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center text-gray-400">
                                <i class="fa-solid fa-file-invoice text-3xl mb-2"></i>
                                <p class="text-sm">No procurement records found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
