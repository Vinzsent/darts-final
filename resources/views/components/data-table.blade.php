@props(['id' => 'data-table', 'headers' => [], 'searchable' => true, 'actions' => true])

<div x-data="{ search: '' }" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    @if($searchable)
    <div class="p-4 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="relative max-w-sm">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" x-model="search" placeholder="Search..." class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            {{ $slot ?? '' }}
        </div>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            @if(count($headers) > 0)
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $key => $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
                    @endforeach
                    @if($actions)
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    @endif
                </tr>
            </thead>
            @endif
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $body ?? '' }}
            </tbody>
        </table>
    </div>
    @if(isset($pagination))
    <div class="px-6 py-4 border-t border-gray-200">{{ $pagination }}</div>
    @endif
</div>
