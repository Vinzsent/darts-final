@extends(request()->has('modal') ? 'layouts.blank' : 'layouts.app')

@section('title', 'Supply Request #' . $supplyRequest->request_id . ' - DARTS')
@section('page-title', 'Supply Request #' . $supplyRequest->request_id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Breadcrumb --}}
    @if(!request()->has('modal'))
    <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="{{ route('supply-requests.index') }}" class="hover:text-emerald-600">Supply Requests</a>
        <i class="fa-solid fa-chevron-right text-xs"></i>
        <span class="text-gray-900">#{{ $supplyRequest->request_id }}</span>
    </div>
    @endif

    {{-- Action Buttons --}}
    @if(!request()->has('modal'))
    <div class="flex items-center justify-between">
        <div>
            @php
                $statusColors = [
                    'Pending' => 'bg-yellow-100 text-yellow-800',
                    'Noted' => 'bg-blue-100 text-blue-800',
                    'Checked' => 'bg-indigo-100 text-indigo-800',
                    'Verified' => 'bg-purple-100 text-purple-800',
                    'Approved' => 'bg-emerald-100 text-emerald-800',
                    'Issued' => 'bg-green-100 text-green-800',
                    'Rejected' => 'bg-red-100 text-red-800',
                ];
                $color = $statusColors[$supplyRequest->status] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                {{ $supplyRequest->status }}
            </span>
            <span class="ml-2 text-sm text-gray-500">
                Requested {{ \Carbon\Carbon::parse($supplyRequest->date_requested)->format('M d, Y') }}
            </span>
        </div>
        <div class="flex items-center space-x-2">
            @if(in_array($supplyRequest->status, ['Pending', 'Rejected']))
                <a href="{{ route('supply-requests.edit', $supplyRequest->request_id) }}"
                   class="px-3 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                </a>
            @endif
            <a href="{{ route('supply-requests.index') }}"
               class="px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
    @else
    {{-- Modal: just show status badge --}}
    @php
        $statusColors = [
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Noted' => 'bg-blue-100 text-blue-800',
            'Checked' => 'bg-indigo-100 text-indigo-800',
            'Verified' => 'bg-purple-100 text-purple-800',
            'Approved' => 'bg-emerald-100 text-emerald-800',
            'Issued' => 'bg-green-100 text-green-800',
            'Rejected' => 'bg-red-100 text-red-800',
        ];
        $color = $statusColors[$supplyRequest->status] ?? 'bg-gray-100 text-gray-800';
    @endphp
    <div class="flex items-center space-x-3">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
            {{ $supplyRequest->status }}
        </span>
        <span class="text-sm text-gray-500">
            Requested {{ \Carbon\Carbon::parse($supplyRequest->date_requested)->format('M d, Y') }}
        </span>
    </div>
    @endif

    {{-- Approval Workflow Progress --}}
    @php
        $steps = [
            'noted' => ['label' => 'Noted', 'icon' => 'fa-eye', 'by' => $supplyRequest->noter, 'date' => $supplyRequest->noted_date],
            'checked' => ['label' => 'Checked', 'icon' => 'fa-check-double', 'by' => $supplyRequest->checker, 'date' => $supplyRequest->checked_date],
            'verified' => ['label' => 'Verified', 'icon' => 'fa-shield', 'by' => $supplyRequest->verifier, 'date' => $supplyRequest->verified_date],
            'approved' => ['label' => 'Approved', 'icon' => 'fa-thumbs-up', 'by' => $supplyRequest->approver, 'date' => $supplyRequest->approved_date],
            'issued' => ['label' => 'Issued', 'icon' => 'fa-check-circle', 'by' => $supplyRequest->issuer, 'date' => $supplyRequest->issued_date],
        ];

        $order = ['Pending' => 0, 'Noted' => 1, 'Checked' => 2, 'Verified' => 3, 'Approved' => 4, 'Issued' => 5, 'Rejected' => -1];
        $currentOrder = $order[$supplyRequest->status] ?? 0;
        $stepIndex = 0;
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fa-solid fa-road text-emerald-600 mr-2"></i>Approval Workflow
        </h3>

        {{-- Progress Bar --}}
        @if($supplyRequest->status !== 'Rejected')
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 font-medium">Progress</span>
                    <span class="text-xs text-gray-500">{{ $currentOrder > 0 ? round(($currentOrder / 5) * 100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $currentOrder > 0 ? round(($currentOrder / 5) * 100) : 0 }}%">
                    </div>
                </div>
            </div>
        @endif

        {{-- Steps --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($steps as $key => $step)
                @php
                    $stepIndex++;
                    $isComplete = !is_null($step['by']);
                    $isCurrent = $step['label'] === $supplyRequest->status;
                    $isRejected = $supplyRequest->status === 'Rejected';
                @endphp

                <div class="relative flex flex-col items-center text-center p-4 rounded-lg border-2 transition
                    {{ $isComplete ? 'border-emerald-500 bg-emerald-50' : ($isCurrent ? 'border-emerald-400 bg-emerald-50/50' : 'border-gray-200 bg-gray-50') }}">
                    {{-- Step Number --}}
                    <div class="absolute -top-3 -right-3 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                        {{ $isComplete ? 'bg-emerald-600 text-white' : ($isCurrent ? 'bg-emerald-400 text-white' : 'bg-gray-300 text-gray-600') }}">
                        {{ $stepIndex }}
                    </div>

                    {{-- Icon --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2
                        {{ $isComplete ? 'bg-emerald-600 text-white' : ($isCurrent ? 'bg-emerald-400 text-white' : 'bg-gray-300 text-gray-500') }}">
                        @if($isComplete)
                            <i class="fa-solid fa-check"></i>
                        @elseif($isRejected)
                            <i class="fa-solid fa-times"></i>
                        @else
                            <i class="fa-solid {{ $step['icon'] }}"></i>
                        @endif
                    </div>

                    {{-- Label --}}
                    <span class="text-sm font-semibold {{ $isComplete ? 'text-emerald-800' : ($isCurrent ? 'text-emerald-700' : 'text-gray-500') }}">
                        {{ $step['label'] }}
                    </span>

                    {{-- Person & Date --}}
                    @if($isComplete)
                        <span class="text-xs text-gray-600 mt-1">{{ $step['by']?->display_name }}</span>
                        @if($step['date'])
                            <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($step['date'])->format('M d, Y h:i A') }}</span>
                        @else
                            <span class="text-xs text-gray-400">Date not recorded</span>
                        @endif
                    @elseif($isCurrent && !$isRejected)
                        <span class="text-xs text-emerald-600 font-medium mt-1">In Progress</span>
                    @elseif($isRejected)
                        <span class="text-xs text-red-600 font-medium mt-1">Stopped</span>
                    @else
                        <span class="text-xs text-gray-400 mt-1">Pending</span>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Approval / Reject Actions --}}
        @if(!request()->has('modal'))
        @if(in_array($supplyRequest->status, ['Pending', 'Noted', 'Checked', 'Verified', 'Approved']))
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">
                        Current step: <span class="font-semibold text-emerald-700">{{ $supplyRequest->status }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <form method="POST" action="{{ route('supply-requests.reject', $supplyRequest->request_id) }}"
                          onsubmit="return confirm('Reject this request?')">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <input type="text" name="reason" placeholder="Rejection reason..." required
                                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 w-48">
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                                <i class="fa-solid fa-ban mr-1"></i> Reject
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('supply-requests.approve', $supplyRequest->request_id) }}">
                        @csrf
                        @php
                            $stepMap = ['Pending' => 'noted', 'Noted' => 'checked', 'Checked' => 'verified', 'Verified' => 'approved', 'Approved' => 'issued'];
                            $nextStep = $stepMap[$supplyRequest->status] ?? '';
                            $stepLabels = ['noted' => 'Note', 'checked' => 'Check', 'verified' => 'Verify', 'approved' => 'Approve', 'issued' => 'Issue'];
                        @endphp
                        <input type="hidden" name="step" value="{{ $nextStep }}">
                        <button type="submit"
                                class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                            <i class="fa-solid fa-check mr-1"></i> {{ $stepLabels[$nextStep] ?? 'Approve' }}
                        </button>
                    </form>
                </div>
            </div>
        @elseif($supplyRequest->status === 'Rejected')
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">Request Rejected</p>
                            @if($supplyRequest->remarks)
                                <p class="text-sm text-red-600 mt-1">Reason: {{ $supplyRequest->remarks }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @endif {{-- end modal guard --}}
    </div>

    {{-- Request Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fa-solid fa-file-lines text-emerald-600 mr-2"></i>Request Details
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Requestor</dt>
                        <dd class="mt-1 text-sm font-medium text-gray-900">{{ $supplyRequest->user?->display_name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Department / Unit</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->department_unit }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->purpose }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date Needed</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($supplyRequest->date_needed)->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Date Requested</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($supplyRequest->date_requested)->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
            <div>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Sales Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->sales_type ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Request Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->request_type ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->semester ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">School Year</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->school_year ?? 'N/A' }}</dd>
                    </div>
                    @if($supplyRequest->remarks)
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $supplyRequest->remarks }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Item Details --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fa-solid fa-box text-emerald-600 mr-2"></i>Item Details
            </h3>
        </div>
        <div class="p-6">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left px-3 py-2 font-semibold text-gray-600">Item</th>
                        <th class="text-left px-3 py-2 font-semibold text-gray-600">Category</th>
                        <th class="text-left px-3 py-2 font-semibold text-gray-600">Brand</th>
                        <th class="text-left px-3 py-2 font-semibold text-gray-600">Color/Size/Type</th>
                        <th class="text-center px-3 py-2 font-semibold text-gray-600">Qty</th>
                        <th class="text-center px-3 py-2 font-semibold text-gray-600">Unit</th>
                        <th class="text-right px-3 py-2 font-semibold text-gray-600">Unit Cost</th>
                        <th class="text-right px-3 py-2 font-semibold text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="px-3 py-3">
                            <div class="font-medium text-gray-900">{{ $supplyRequest->item_name }}</div>
                            @if($supplyRequest->request_description)
                                <div class="text-xs text-gray-500 mt-0.5">{{ $supplyRequest->request_description }}</div>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-gray-700">{{ $supplyRequest->category }}</td>
                        <td class="px-3 py-3 text-gray-700">{{ $supplyRequest->brand ?? 'N/A' }}</td>
                        <td class="px-3 py-3 text-gray-700">
                            @php
                                $attrs = array_filter([$supplyRequest->color, $supplyRequest->size, $supplyRequest->type]);
                            @endphp
                            {{ $attrs ? implode(' / ', $attrs) : 'N/A' }}
                        </td>
                        <td class="px-3 py-3 text-center font-medium text-gray-900">{{ $supplyRequest->quantity_requested }}</td>
                        <td class="px-3 py-3 text-center text-gray-700">{{ $supplyRequest->unit }}</td>
                        <td class="px-3 py-3 text-right text-gray-700">
                            @if($supplyRequest->unit_cost)
                                PHP {{ number_format($supplyRequest->unit_cost, 2) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-3 py-3 text-right font-medium text-gray-900">
                            @if($supplyRequest->total_cost)
                                PHP {{ number_format($supplyRequest->total_cost, 2) }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Approval Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fa-solid fa-timeline text-emerald-600 mr-2"></i>Approval Timeline
        </h3>
        <div class="space-y-0">
            @php
                $timeline = [
                    ['label' => 'Created', 'icon' => 'fa-plus-circle', 'color' => 'gray',
                     'person' => $supplyRequest->user?->display_name,
                     'date' => $supplyRequest->date_requested],
                    ['label' => 'Noted', 'icon' => 'fa-eye', 'color' => 'blue',
                     'person' => $supplyRequest->noter?->display_name,
                     'date' => $supplyRequest->noted_date],
                    ['label' => 'Checked', 'icon' => 'fa-check-double', 'color' => 'indigo',
                     'person' => $supplyRequest->checker?->display_name,
                     'date' => $supplyRequest->checked_date],
                    ['label' => 'Verified', 'icon' => 'fa-shield', 'color' => 'purple',
                     'person' => $supplyRequest->verifier?->display_name,
                     'date' => $supplyRequest->verified_date],
                    ['label' => 'Approved', 'icon' => 'fa-thumbs-up', 'color' => 'emerald',
                     'person' => $supplyRequest->approver?->display_name,
                     'date' => $supplyRequest->approved_date],
                    ['label' => 'Issued', 'icon' => 'fa-check-circle', 'color' => 'green',
                     'person' => $supplyRequest->issuer?->display_name,
                     'date' => $supplyRequest->issued_date],
                ];

                $colors = [
                    'gray' => ['bg' => 'bg-gray-400', 'text' => 'text-gray-600'],
                    'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-700'],
                    'indigo' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-700'],
                    'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-700'],
                    'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                    'green' => ['bg' => 'bg-green-500', 'text' => 'text-green-700'],
                ];
            @endphp

            @foreach($timeline as $i => $entry)
                @php
                    $isDone = !is_null($entry['date']);
                    $c = $colors[$entry['color']] ?? $colors['gray'];
                @endphp
                <div class="flex items-start space-x-3 pb-6 relative">
                    {{-- Connector Line --}}
                    @if(!$loop->last)
                        <div class="absolute left-[15px] top-8 bottom-0 w-0.5 {{ $isDone ? 'bg-emerald-300' : 'bg-gray-200' }}"></div>
                    @endif

                    {{-- Dot --}}
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 {{ $isDone ? $c['bg'] . ' text-white' : 'bg-gray-200 text-gray-400' }}">
                        <i class="fa-solid {{ $entry['icon'] }} text-xs"></i>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium {{ $isDone ? 'text-gray-900' : 'text-gray-400' }}">
                            {{ $entry['label'] }}
                        </p>
                        @if($isDone)
                            <p class="text-xs text-gray-600 mt-0.5">{{ $entry['person'] }}</p>
                            @if($entry['date'])
                                <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($entry['date'])->format('M d, Y h:i A') }}</p>
                            @else
                                <p class="text-xs text-gray-400">Date not recorded</p>
                            @endif
                        @else
                            <p class="text-xs text-gray-400 mt-0.5">Pending</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
