@extends(request()->has('modal') ? 'layouts.blank' : 'layouts.app')

@section('title', $item->item_name . ' - Inventory - DARTS')
@section('page-title', $item->item_name)

@section('content')
<div class="space-y-6">
    {{-- Back link --}}
    @if(!request()->has('modal'))
    <a href="{{ route('inventory.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Inventory
    </a>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Item Details Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-box text-emerald-600"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Item Details</h3>
                    </div>
                    @if(!request()->has('modal'))
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('inventory.edit', $item->inventory_id) }}" class="px-3 py-1.5 text-sm bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                        </a>
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->item_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->category ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->brand ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Type</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->type ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Color</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->color ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Size</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->size ?? '--' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->description ?? 'No description.' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->location ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Receiver</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->receiver ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $item->supplier->supplier_name ?? '--' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</dt>
                            <dd class="mt-1">
                                @if($item->status === 'Active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                                @elseif($item->status === 'Inactive')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Discontinued</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Stock Movement History --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-clock-rotate-left text-blue-600"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Stock Movement History</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($item->stockLogs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $log->date_created ? date('M d, Y h:i A', strtotime($log->date_created)) : '--' }}</td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    @if($log->movement_type === 'IN')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">IN</span>
                                    @elseif($log->movement_type === 'OUT')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">OUT</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">{{ $log->movement_type }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium {{ $log->movement_type === 'IN' ? 'text-green-600' : ($log->movement_type === 'OUT' ? 'text-red-600' : 'text-gray-900') }}">
                                    {{ $log->movement_type === 'IN' ? '+' : ($log->movement_type === 'OUT' ? '-' : '') }}{{ number_format($log->quantity) }}
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">{{ $log->previous_stock }} → {{ $log->new_stock }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500 max-w-[200px] truncate">{{ $log->notes ?? '--' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                    <i class="fa-solid fa-box-open text-2xl mb-2"></i>
                                    <p>No stock movements recorded.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Stock Summary --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Stock Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Current Stock</span>
                        <span class="text-lg font-bold {{ $item->is_low_stock ? ($item->current_stock <= 0 ? 'text-red-600' : 'text-amber-600') : 'text-gray-900' }}">
                            {{ number_format($item->current_stock) }}
                        </span>
                    </div>
                    @if($item->unit)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Unit</span>
                        <span class="text-sm font-medium text-gray-900">{{ $item->unit }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Reorder Level</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($item->reorder_level) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Unit Cost</span>
                        <span class="text-sm font-medium text-gray-900">&#8369;{{ number_format($item->unit_cost, 2) }}</span>
                    </div>
                    <hr class="border-gray-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Stock Status</span>
                        @if($item->stock_status === 'out')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">Out of Stock</span>
                        @elseif($item->stock_status === 'low')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-700">Low Stock</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">In Stock</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Stock Adjustment --}}
            @if(!request()->has('modal'))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Adjust Stock</h3>
                <form action="{{ route('inventory.stock-adjust', $item->inventory_id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
                            <option value="add">Add Stock</option>
                            <option value="subtract">Remove Stock</option>
                            <option value="set">Set to Exact</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Quantity</label>
                        <input type="number" name="quantity" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                        <input type="text" name="notes" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500" placeholder="Reason for adjustment">
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        <i class="fa-solid fa-pen mr-1"></i> Apply Adjustment
                    </button>
                </form>
            </div>
            @endif

            {{-- Audit Info --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Audit Information</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Created By</span>
                        <p class="font-medium text-gray-900">{{ $item->creator->display_name ?? '--' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Date Created</span>
                        <p class="font-medium text-gray-900">{{ $item->date_created ? date('M d, Y h:i A', strtotime($item->date_created)) : '--' }}</p>
                    </div>
                    @if($item->last_updated_by)
                    <div>
                        <span class="text-gray-500">Last Updated By</span>
                        <p class="font-medium text-gray-900">{{ $item->updater->display_name ?? '--' }}</p>
                    </div>
                    @endif
                    @if($item->date_updated)
                    <div>
                        <span class="text-gray-500">Date Updated</span>
                        <p class="font-medium text-gray-900">{{ date('M d, Y h:i A', strtotime($item->date_updated)) }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
