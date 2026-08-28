@extends('layouts.app')

@section('title', 'Personnel - DARTS')
@section('page-title', 'Personnel')

@section('content')
<div class="space-y-6 min-w-0">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Personnel Directory</h1>
            <p class="text-sm text-slate-500 mt-1">Browse employees, view profiles, and track checked-out assets.</p>
        </div>
        <span class="inline-flex items-center gap-2 self-start rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 shrink-0">
            <i class="fa-solid fa-users"></i> {{ $employees->total() }} Employees
        </span>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 min-w-0">
        <form method="GET" action="{{ route('personnel.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 min-w-0">
            <div class="sm:col-span-2 min-w-0">
                <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
                <div class="relative">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" id="personnelSearch" name="search" value="{{ $search }}" placeholder="Name, personnel number, email, position..."
                           class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-0">
                </div>
            </div>
            <div class="min-w-0">
                <label class="block text-xs font-medium text-slate-500 mb-1">Department</label>
                <select name="department" id="personnelDepartment" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-0">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ $department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2 min-w-0">
                <div class="flex-1 min-w-0">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
                    <select name="status" id="personnelStatus" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-0">
                        <option value="">All</option>
                        @foreach($statuses as $st)
                            <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('personnel.index') }}" class="px-3 py-2 bg-slate-100 text-slate-600 text-sm rounded-lg hover:bg-slate-200 transition shrink-0" title="Reset">
                    <i class="fa-solid fa-rotate"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Employee Grid --}}
    @if($employees->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($employees as $emp)
        @php $initials = strtoupper(substr($emp->first_name ?? '', 0, 1) . substr($emp->last_name ?? '', 0, 1)); @endphp
        <button type="button" onclick="openPersonnelModal({{ $emp->id }})"
                class="personnel-card group text-left bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <div class="flex items-start gap-4">
                <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-900 text-white text-sm font-bold shadow-sm">
                    {{ $initials ?: '?' }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-900 group-hover:text-emerald-700 transition">{{ $emp->display_name }}</p>
                    <p class="truncate text-xs text-slate-500 mt-0.5">{{ $emp->position ?: '—' }}</p>
                    <p class="truncate text-xs text-slate-400 mt-0.5"><i class="fa-solid fa-id-badge mr-1"></i>{{ $emp->eid ?: 'No ID' }}</p>
                </div>
            </div>
            <div class="mt-4 flex items-center justify-between">
                <span class="truncate text-xs text-slate-500">{{ $emp->department ?: 'Unassigned' }}</span>
                @if(strtolower($emp->status ?? '') === 'active')
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Active</span>
                @else
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">{{ ucfirst($emp->status ?: 'N/A') }}</span>
                @endif
            </div>
        </button>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 py-16 text-center">
        <i class="fa-solid fa-user-slash text-4xl text-slate-300 mb-3"></i>
        <p class="text-slate-500 text-sm">No personnel found matching your filters.</p>
    </div>
    @endif

    @if($employees->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 px-6 py-4">
        {{ $employees->links() }}
    </div>
    @endif
</div>

{{-- Personnel Detail Modal --}}
<div id="personnelModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm transition-opacity" onclick="closePersonnelModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[92vh] overflow-hidden flex flex-col">
            {{-- Title bar --}}
            <div class="px-6 py-4 bg-emerald-700 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span id="pmAvatar" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white text-sm font-bold">?</span>
                    <h3 id="pmTitle" class="truncate text-base font-bold text-white">Loading...</h3>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button type="button" onclick="window.print()" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition" title="Preview Report">
                        <i class="fa-solid fa-print"></i>
                    </button>
                    <button type="button" onclick="closePersonnelModal()" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="p-6 overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    {{-- Contact Information --}}
                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wide text-slate-600">Contact Information</div>
                        <dl class="p-4 space-y-2.5 text-sm">
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-slate-500">Name:</dt><dd id="pmName" class="font-medium text-slate-900">—</dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-slate-500">Email:</dt><dd id="pmEmail" class="font-medium text-slate-900 break-all">—</dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-slate-500">Campus:</dt><dd id="pmCampus" class="font-medium text-slate-900">—</dd></div>
                            <div class="flex gap-2"><dt class="w-20 shrink-0 text-slate-500">Emp. Type:</dt><dd id="pmEmpType" class="font-medium text-slate-900">—</dd></div>
                        </dl>
                    </div>

                    {{-- Personnel Number + Organization --}}
                    <div class="space-y-4">
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wide text-slate-600">Personnel Number</div>
                            <div class="p-4 flex items-center gap-3">
                                <i class="fa-solid fa-barcode text-2xl text-slate-400"></i>
                                <span id="pmEid" class="px-3 py-1.5 rounded-lg bg-slate-100 font-mono text-sm font-semibold text-slate-900">—</span>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 overflow-hidden">
                            <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wide text-slate-600">Organization Information</div>
                            <dl class="p-4 space-y-2.5 text-sm">
                                <div class="flex gap-2"><dt class="w-16 shrink-0 text-slate-500">Title:</dt><dd id="pmPosition" class="font-medium text-slate-900">—</dd></div>
                                <div class="flex gap-2"><dt class="w-16 shrink-0 text-slate-500">Group:</dt><dd id="pmDepartment" class="font-medium text-slate-900">—</dd></div>
                                <div class="flex gap-2 items-center"><dt class="w-16 shrink-0 text-slate-500">Status:</dt><dd><span id="pmStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">—</span></dd></div>
                            </dl>
                        </div>
                    </div>

                    {{-- Photo --}}
                    <div class="rounded-xl border border-slate-200 flex flex-col overflow-hidden">
                        <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wide text-slate-600">Photo</div>
                        <div class="flex-1 flex items-center justify-center p-4 bg-slate-50">
                            <span id="pmPhoto" class="inline-flex h-28 w-28 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-900 text-white text-3xl font-bold shadow-inner">?</span>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="mt-6 border-b border-slate-200 flex gap-1 overflow-x-auto">
                    <button type="button" data-ptab="assets" onclick="switchPtab('assets')" class="ptab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition whitespace-nowrap">
                        <i class="fa-solid fa-boxes-packing mr-1.5"></i>Assets Checked Out <span id="pmAssetCount" class="ml-1 rounded-full bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold">0</span>
                    </button>
                    <button type="button" data-ptab="attachments" onclick="switchPtab('attachments')" class="ptab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition whitespace-nowrap">
                        <i class="fa-solid fa-paperclip mr-1.5"></i>Attachments <span id="pmAttachmentCount" class="ml-1 rounded-full bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold">0</span>
                    </button>
                    <button type="button" data-ptab="notes" onclick="switchPtab('notes')" class="ptab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition whitespace-nowrap">
                        <i class="fa-solid fa-note-sticky mr-1.5"></i>Notes <span id="pmNoteCount" class="ml-1 rounded-full bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold">0</span>
                    </button>
                    <button type="button" data-ptab="history" onclick="switchPtab('history')" class="ptab-btn px-4 py-2.5 text-sm font-semibold rounded-t-lg transition whitespace-nowrap">
                        <i class="fa-solid fa-clock-rotate-left mr-1.5"></i>History <span id="pmHistoryCount" class="ml-1 rounded-full bg-slate-200 px-1.5 py-0.5 text-[10px] font-bold">0</span>
                    </button>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 flex flex-wrap gap-2 text-xs text-slate-500">
                        <button type="button" onclick="openCheckInModal()" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200 hover:border-emerald-400 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer"><i class="fa-solid fa-right-to-bracket text-emerald-600"></i> Check In</button>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200"><i class="fa-solid fa-right-from-bracket text-red-500"></i> Check Out (F3)</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200"><i class="fa-solid fa-folder-open text-amber-500"></i> Open</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200"><i class="fa-solid fa-file-export text-blue-500"></i> Checked Out Report</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white border border-slate-200"><i class="fa-solid fa-receipt text-violet-500"></i> Receipt</span>
                    </div>

                    <div id="ptab-assets" class="ptab-panel p-4">
                        <div id="pmAssetsEmpty" class="hidden py-10 text-center">
                            <p class="italic text-sm text-slate-400">This person does not currently have any assets checked out.</p>
                        </div>
                        <div id="pmAssetsWrap" class="hidden overflow-x-auto">
                            <table class="w-full min-w-[720px] divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Date Out</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Asset</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Category</th>
                                        <th class="px-4 py-2.5 text-right text-xs font-bold uppercase text-slate-500">Qty</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="pmAssetsBody" class="divide-y divide-slate-100"></tbody>
                            </table>
                        </div>
                    </div>

                    <div id="ptab-attachments" class="ptab-panel hidden p-4">
                        <div id="pmAttachmentsEmpty" class="py-10 text-center">
                            <p class="italic text-sm text-slate-400">No attachments on file for this person.</p>
                        </div>
                        <div id="pmAttachmentsWrap" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
                    </div>

                    <div id="ptab-notes" class="ptab-panel hidden p-4">
                        <div id="pmNotesEmpty" class="py-10 text-center">
                            <p class="italic text-sm text-slate-400">No notes recorded for this person.</p>
                        </div>
                        <div id="pmNotesWrap" class="hidden space-y-3"></div>
                    </div>

                    <div id="ptab-history" class="ptab-panel hidden p-4">
                        <div id="pmHistoryEmpty" class="hidden py-10 text-center">
                            <p class="italic text-sm text-slate-400">No transaction history for this person.</p>
                        </div>
                        <div id="pmHistoryWrap" class="hidden overflow-x-auto">
                            <table class="w-full min-w-[720px] divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Date</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Asset</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Type</th>
                                        <th class="px-4 py-2.5 text-right text-xs font-bold uppercase text-slate-500">Qty</th>
                                        <th class="px-4 py-2.5 text-left text-xs font-bold uppercase text-slate-500">Returned</th>
                                    </tr>
                                </thead>
                                <tbody id="pmHistoryBody" class="divide-y divide-slate-100"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end shrink-0">
                <button type="button" onclick="closePersonnelModal()" class="px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Save &amp; Close
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Check In Modal --}}
<div id="checkinModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
        <div class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" onclick="closeCheckInModal()"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md sm:max-w-2xl max-h-[92vh] overflow-hidden flex flex-col">
            {{-- Title bar --}}
            <div class="px-4 py-3 sm:px-6 sm:py-4 bg-emerald-700 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/15 text-white"><i class="fa-solid fa-right-to-bracket"></i></span>
                    <div class="min-w-0">
                        <h3 class="text-base font-bold text-white leading-tight">Check In</h3>
                        <p id="ciSubtitle" class="text-xs text-white/70 truncate">Select the items this person is returning.</p>
                    </div>
                </div>
                <button type="button" onclick="closeCheckInModal()" class="p-2 text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="checkinForm" class="p-4 sm:p-6 space-y-4 sm:space-y-5 overflow-y-auto" onsubmit="return false;">
                @csrf
                <input type="hidden" name="personnel_id" id="ciPersonnelId">

                {{-- Items to Check In --}}
                <div class="rounded-xl border border-slate-200 overflow-hidden">
                    <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wide text-slate-600">Items to Check In</div>
                    <div class="overflow-x-auto max-h-56 overflow-y-auto">
                        <table class="w-full min-w-[560px] divide-y divide-slate-200">
                            <thead class="bg-slate-50 sticky top-0">
                                <tr>
                                    <th class="w-10 px-3 py-2"></th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase text-slate-500">Asset Num.</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase text-slate-500">Description</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase text-slate-500">Checked Out</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase text-slate-500">Due</th>
                                </tr>
                            </thead>
                            <tbody id="ciItemsBody" class="divide-y divide-slate-100"></tbody>
                        </table>
                    </div>
                    <div id="ciItemsEmpty" class="hidden px-4 py-8 text-center">
                        <p class="italic text-sm text-slate-400">This person has no checked-out items to check in.</p>
                    </div>
                    <div class="px-4 py-3 border-t border-slate-200 flex items-center gap-2 bg-slate-50">
                        <input type="text" id="ciSearch" placeholder="Filter items..." class="flex-1 px-3 py-1.5 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 min-w-0">
                        <button type="button" onclick="filterCiItems()" class="px-3 py-1.5 bg-white border border-slate-300 text-slate-600 text-sm rounded-lg hover:bg-slate-100 transition shrink-0">
                            <i class="fa-solid fa-magnifying-glass mr-1 text-emerald-600"></i>Search
                        </button>
                    </div>
                </div>

                {{-- Status / Location / Comments --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Set Status To:</label>
                        <select name="status" id="ciStatus" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="In Storage">In Storage</option>
                            <option value="In Use">In Use</option>
                            <option value="For Repair">For Repair</option>
                            <option value="Retired">Retired</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Set Location To:</label>
                        <select name="location" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-slate-50 text-slate-500" disabled>
                            <option>*No Change*</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Comments:</label>
                    <textarea name="comments" rows="3" placeholder="Optional notes about this check-in..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                </div>

                <label class="flex items-center justify-end gap-2 text-sm text-slate-600 cursor-pointer select-none">
                    <input type="checkbox" name="print_receipt" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    Print Check In Receipt
                </label>

                {{-- Footer --}}
                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-slate-200">
                    <span id="ciError" class="hidden text-sm text-red-600"><i class="fa-solid fa-circle-exclamation mr-1"></i>Select at least one item.</span>
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3 w-full sm:w-auto">
                        <button type="button" id="ciCompleteBtn" onclick="completeCheckIn()" class="w-full sm:w-auto px-5 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm disabled:opacity-50">
                            <i class="fa-solid fa-circle-check mr-2"></i>Complete Check In
                        </button>
                        <button type="button" onclick="closeCheckInModal()" class="w-full sm:w-auto px-5 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-200 transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Debounced search
    let searchTimer;
    const searchInput = document.getElementById('personnelSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => this.form.submit(), 400);
        });
    }
    ['personnelDepartment', 'personnelStatus'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', function () { this.form.submit(); });
    });

    function esc(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }
    function fmtDate(v) {
        if (!v) return '—';
        const d = new Date(v);
        return isNaN(d) ? '—' : d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function openPersonnelModal(id) {
        window._personnelId = id;
        document.getElementById('personnelModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        switchPtab('assets');
        document.getElementById('pmTitle').textContent = 'Loading...';

        fetch(`/personnel/${id}`)
            .then(r => { if (!r.ok) throw new Error('Failed'); return r.json(); })
            .then(data => renderPersonnel(data))
            .catch(() => {
                document.getElementById('pmTitle').textContent = 'Failed to load personnel details.';
            });
    }

    function closePersonnelModal() {
        document.getElementById('personnelModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            if (!document.getElementById('checkinModal').classList.contains('hidden')) closeCheckInModal();
            else closePersonnelModal();
        }
    });

    function renderPersonnel(data) {
        window._personnelData = data;
        const e = data.employee;
        const fullName = [e.title, e.first_name, e.middle_name, e.last_name, e.suffix].filter(Boolean).join(' ');
        const initials = (((e.first_name || '')[0] || '') + ((e.last_name || '')[0] || '')).toUpperCase();
        document.getElementById('pmTitle').textContent = `${e.last_name || ''}, ${e.first_name || ''}${e.eid ? ` (${e.eid})` : ''}`;
        document.getElementById('pmAvatar').textContent = initials || '?';
        document.getElementById('pmPhoto').textContent = initials || '?';
        document.getElementById('pmName').textContent = fullName || '—';
        document.getElementById('pmEmail').textContent = e.email || '—';
        document.getElementById('pmCampus').textContent = e.campus || '—';
        document.getElementById('pmEmpType').textContent = e.employment_type || '—';
        document.getElementById('pmEid').textContent = e.eid || 'Not assigned';
        document.getElementById('pmPosition').textContent = e.position || '—';
        document.getElementById('pmDepartment').textContent = e.department || '—';

        const statusEl = document.getElementById('pmStatus');
        statusEl.textContent = e.status ? e.status.charAt(0).toUpperCase() + e.status.slice(1) : '—';
        const active = String(e.status || '').toLowerCase() === 'active';
        statusEl.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600'}`;

        const assets = data.checked_out || [];
        document.getElementById('pmAssetCount').textContent = assets.length;
        document.getElementById('pmAssetsEmpty').classList.toggle('hidden', assets.length > 0);
        document.getElementById('pmAssetsWrap').classList.toggle('hidden', assets.length === 0);
        document.getElementById('pmAssetsBody').innerHTML = assets.map(a => `
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-2.5 text-sm text-slate-600 whitespace-nowrap">${fmtDate(a.date_requested)}</td>
                <td class="px-4 py-2.5 text-sm font-medium text-slate-900">${esc(a.item_name)}${a.brand ? ` <span class="text-slate-400 font-normal">(${esc(a.brand)})</span>` : ''}</td>
                <td class="px-4 py-2.5 text-sm text-slate-600">${esc(a.category) || '—'}</td>
                <td class="px-4 py-2.5 text-sm text-slate-900 text-right">${esc(a.quantity_requested)}</td>
                <td class="px-4 py-2.5"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">${esc(a.status) || 'Out'}</span></td>
            </tr>`).join('');

        document.getElementById('pmAttachmentCount').textContent = (data.attachments || []).length;
        document.getElementById('pmNoteCount').textContent = (data.notes || []).length;

        const history = data.history || [];
        document.getElementById('pmHistoryCount').textContent = history.length;
        document.getElementById('pmHistoryEmpty').classList.toggle('hidden', history.length > 0);
        document.getElementById('pmHistoryWrap').classList.toggle('hidden', history.length === 0);
        document.getElementById('pmHistoryBody').innerHTML = history.map(h => `
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-2.5 text-sm text-slate-600 whitespace-nowrap">${fmtDate(h.date_requested)}</td>
                <td class="px-4 py-2.5 text-sm font-medium text-slate-900">${esc(h.item_name)}</td>
                <td class="px-4 py-2.5 text-sm text-slate-600">${esc(h.request_type) || '—'}</td>
                <td class="px-4 py-2.5 text-sm text-slate-900 text-right">${esc(h.quantity_requested)}</td>
                <td class="px-4 py-2.5 text-sm whitespace-nowrap">${h.date_return
                    ? `<span class="text-emerald-600 font-medium">${fmtDate(h.date_return)}</span>`
                    : '<span class="text-slate-400">Pending</span>'}</td>
            </tr>`).join('');
    }

    function switchPtab(name) {
        document.querySelectorAll('.ptab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById(`ptab-${name}`).classList.remove('hidden');
        document.querySelectorAll('.ptab-btn').forEach(b => {
            const isActive = b.dataset.ptab === name;
            b.classList.toggle('bg-emerald-50', isActive);
            b.classList.toggle('text-emerald-700', isActive);
            b.classList.toggle('border-b-2', isActive);
            b.classList.toggle('border-emerald-600', isActive);
            b.classList.toggle('text-slate-500', !isActive);
            b.classList.toggle('hover:text-slate-800', !isActive);
        });
    }
    switchPtab('assets');

    // ===== Check In Modal =====
    function openCheckInModal() {
        const data = window._personnelData;
        if (!data) return;

        const custodian = `${data.employee.last_name || ''}, ${data.employee.first_name || ''}`.replace(/^, |, $/, '');
        const items = data.checked_out || [];
        const body = document.getElementById('ciItemsBody');

        body.innerHTML = items.map(a => `
            <tr class="ci-row hover:bg-emerald-50/50" data-text="${esc((a.property_id || '') + ' ' + (a.item_name || '') + ' ' + (a.brand || '')).toLowerCase()}">
                <td class="px-3 py-2"><input type="checkbox" class="ci-check h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" value="${esc(a.property_id)}"></td>
                <td class="px-4 py-2 text-sm font-mono text-slate-700">${esc(a.property_id) || '—'}</td>
                <td class="px-4 py-2 text-sm text-slate-900">${esc(a.item_name)}${a.brand ? ` <span class="text-slate-400">(${esc(a.brand)})</span>` : ''}</td>
                <td class="px-4 py-2 text-sm text-slate-600 whitespace-nowrap">${fmtDate(a.date_requested)}</td>
                <td class="px-4 py-2 text-sm text-slate-600 whitespace-nowrap">${a.date_return ? fmtDate(a.date_return) : '—'}</td>
            </tr>`).join('');

        document.getElementById('ciItemsEmpty').classList.toggle('hidden', items.length > 0);
        document.getElementById('ciSubtitle').textContent = items.length
            ? `${items.length} item(s) checked out to ${custodian || 'this person'}.`
            : 'This person has no assets checked out.';
        document.getElementById('ciSearch').value = '';
        document.getElementById('ciError').classList.add('hidden');
        document.getElementById('ciPersonnelId').value = window._personnelId;

        document.getElementById('checkinModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeCheckInModal() {
        document.getElementById('checkinModal').classList.add('hidden');
        document.body.style.overflow = 'hidden'; // personnel modal still open underneath
    }

    function filterCiItems() {
        const q = document.getElementById('ciSearch').value.trim().toLowerCase();
        document.querySelectorAll('.ci-row').forEach(row => {
            row.style.display = !q || row.dataset.text.includes(q) ? '' : 'none';
        });
    }
    document.getElementById('ciSearch').addEventListener('input', filterCiItems);
    document.getElementById('ciSearch').addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); filterCiItems(); } });

    async function completeCheckIn() {
        const ids = [...document.querySelectorAll('.ci-check:checked')].map(cb => cb.value);
        const err = document.getElementById('ciError');
        if (!ids.length) { err.classList.remove('hidden'); return; }
        err.classList.add('hidden');

        const btn = document.getElementById('ciCompleteBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>Processing...';

        try {
            const res = await fetch(`/personnel/${window._personnelId}/check-in`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('#checkinForm input[name="_token"]').value,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    items: ids.map(Number),
                    status: document.getElementById('ciStatus').value,
                    comments: document.querySelector('#checkinForm textarea[name="comments"]').value,
                }),
            });
            if (!res.ok) throw new Error('Request failed');
            closeCheckInModal();
            openPersonnelModal(window._personnelId); // refresh data
        } catch (ex) {
            err.textContent = 'Check-in failed. Please try again.';
            err.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-circle-check mr-2"></i>Complete Check In';
        }
    }
</script>
@endpush
@endsection
