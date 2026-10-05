<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockLog;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;

class SupplyReportController extends PropertyReportController
{
    protected function mode(): string
    {
        return 'supply';
    }

    protected function tabs(): array
    {
        return [
            'inventory' => ['Supply Inventory', 'fa-solid fa-boxes-stacked'],
            'logs' => ['Stock Movement Logs', 'fa-solid fa-right-left'],
            'issuance' => ['Issuance Logs', 'fa-solid fa-hand-holding-box'],
            'requisitions' => ['Office Requisitions', 'fa-solid fa-file-lines'],
        ];
    }

    protected function reportTypes(): array
    {
        return ['inventory', 'logs', 'issuance', 'requisitions'];
    }

    protected function indexView(): string
    {
        return 'supply-reports.index';
    }

    protected function previewView(): string
    {
        return 'supply-reports._preview';
    }

    protected function printView(): string
    {
        return 'supply-reports.print';
    }

    public function index()
    {
        $categories = Inventory::distinct()->orderBy('category')->pluck('category')->filter()->values();
        $departments = SupplyRequest::distinct()->orderBy('department_unit')->pluck('department_unit')->filter()->values();
        $requestTypes = collect(['Consumable', 'Non-Consumable', 'Replacement'])->values();

        return view('supply-reports.index', [
            'mode' => $this->mode(),
            'tabs' => $this->tabs(),
            'previewUrl' => route('supply-reports.preview'),
            'exportUrl' => route('supply-reports.export'),
            'categories' => $categories,
            'departments' => $departments,
            'requestTypes' => $requestTypes,
        ]);
    }

    public function queryFor(Request $request, string $type)
    {
        if ($type === 'issuance' || $type === 'requisitions') {
            return $this->requestQuery($request, $type);
        }

        if ($type === 'logs') {
            $q = StockLog::with('inventory')->orderBy('date_created', 'desc');

            $from = $request->get('date_from');
            if ($from) { $q->where('date_created', '>=', $from . ' 00:00:00'); }
            $to = $request->get('date_to');
            if ($to) { $q->where('date_created', '<=', $to . ' 23:59:59'); }

            $mtype = trim((string) $request->get('movement_type'));
            if ($mtype !== '') { $q->where('movement_type', $mtype); }

            $search = trim((string) $request->get('search'));
            if ($search !== '') {
                $q->where(function ($q) use ($search) {
                    $q->where('requester_name', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhere('receiver', 'like', "%{$search}%")
                        ->orWhereHas('inventory', function ($p) use ($search) {
                            $p->where('item_name', 'like', "%{$search}%");
                        });
                });
            }

            return $q;
        }

        $q = Inventory::query();

        $search = trim((string) $request->get('search'));
        if ($search !== '') {
            $q->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        $category = trim((string) $request->get('category'));
        if ($category !== '') { $q->where('category', $category); }

        $stock = array_values(array_intersect((array) $request->get('stock_status', []), ['normal', 'low', 'out']));
        if (count($stock) === 1) {
            if ($stock[0] === 'low') {
                $q->whereColumn('current_stock', '<=', 'reorder_level')->where('current_stock', '>', 0);
            } elseif ($stock[0] === 'out') {
                $q->where('current_stock', '<=', 0);
            } else {
                $q->where(function ($q) {
                    $q->whereColumn('current_stock', '>', 'reorder_level')->orWhere('reorder_level', '<=', 0);
                })->where('current_stock', '>', 0);
            }
        } elseif (count($stock) === 2) {
            $ex = array_values(array_diff(['normal', 'low', 'out'], $stock))[0];
            if ($ex === 'normal') { $q->whereColumn('current_stock', '<=', 'reorder_level'); }
            elseif ($ex === 'out') { $q->where('current_stock', '>', 0); }
            else {
                $q->where(function ($q) {
                    $q->whereColumn('current_stock', '>', 'reorder_level')->orWhere('current_stock', '<=', 0);
                });
            }
        }

        $statuses = (array) $request->get('item_status', []);
        if (count($statuses) > 0) { $q->whereIn('status', $statuses); }

        return $q->orderBy('item_name');
    }

    /**
     * Query used by the Issuance Logs and Office Requisitions reports.
     */
    protected function requestQuery(Request $request, string $type)
    {
        $q = SupplyRequest::with('user');

        if ($type === 'issuance') {
            $q->where('status', 'Issued');

            $from = $request->get('date_from');
            if ($from) { $q->where('issued_date', '>=', $from . ' 00:00:00'); }
            $to = $request->get('date_to');
            if ($to) { $q->where('issued_date', '<=', $to . ' 23:59:59'); }
        } else {
            $from = $request->get('date_from');
            if ($from) { $q->where('date_requested', '>=', $from . ' 00:00:00'); }
            $to = $request->get('date_to');
            if ($to) { $q->where('date_requested', '<=', $to . ' 23:59:59'); }

            $statuses = array_values(array_intersect(
                (array) $request->get('req_status', []),
                ['Pending', 'Noted', 'Checked', 'Verified', 'Approved', 'Issued']
            ));
            if (count($statuses) > 0) { $q->whereIn('status', $statuses); }
        }

        $dept = trim((string) $request->get('department'));
        if ($dept !== '') { $q->where('department_unit', $dept); }

        $reqType = trim((string) $request->get('request_type'));
        if ($reqType !== '') { $q->where('request_type', $reqType); }

        $search = trim((string) $request->get('search'));
        if ($search !== '') {
            $q->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('request_description', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%")
                    ->orWhere('issued_by', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        return $q->orderBy($type === 'issuance' ? 'issued_date' : 'date_requested', 'desc')
            ->orderBy('request_id', 'desc');
    }

    protected function totalsFor(Request $request, string $type): array
    {
        if ($type === 'issuance') {
            $amount = 0.0;
            foreach ($this->requestQuery($request, $type)->get() as $r) {
                $amount += (float) $r->amount;
            }

            return ['amount' => $amount];
        }

        if ($type === 'inventory') {
            $valuation = 0.0;
            foreach ($this->queryFor($request, $type)->get() as $i) {
                $valuation += (float) $i->current_stock * (float) $i->unit_cost;
            }

            return ['valuation' => $valuation];
        }

        return [];
    }

    public function rowsFor(Request $request, string $type): array
    {
        if ($type === 'issuance') {
            $data = [];
            $total = 0.0;
            foreach ($this->requestQuery($request, $type)->get() as $r) {
                $qty = $r->quality_issued ?: $r->quantity_requested;
                $total += (float) $r->amount;
                $data[] = [
                    $r->issued_date ? \Carbon\Carbon::parse($r->issued_date)->format('Y-m-d H:i') : '',
                    $r->item_name, $r->department_unit, $r->user?->display_name ?? '',
                    $qty, $r->unit, number_format((float) $r->amount, 2),
                    $r->issued_by ?? '', $r->remarks ?? '',
                ];
            }

            return [
                'header' => ['Date Issued', 'Item', 'Department', 'Requester', 'Qty Issued', 'Unit', 'Amount', 'Issued By', 'Remarks'],
                'data' => $data,
                'footer' => ['', '', '', '', 'Total Issued Amount:', '', number_format($total, 2), '', ''],
            ];
        }

        if ($type === 'requisitions') {
            $data = [];
            foreach ($this->requestQuery($request, $type)->get() as $r) {
                $data[] = [
                    $r->date_requested, $r->item_name, $r->department_unit,
                    $r->user?->display_name ?? '', $r->quantity_requested, $r->unit,
                    $r->request_type ?? '', $r->date_needed, $r->status ?? '',
                ];
            }

            return [
                'header' => ['Date Requested', 'Item', 'Department', 'Requester', 'Qty Requested', 'Unit', 'Request Type', 'Date Needed', 'Status'],
                'data' => $data,
                'footer' => [],
            ];
        }

        if ($type === 'logs') {
            $data = [];
            foreach ($this->queryFor($request, $type)->get() as $l) {
                $data[] = [
                    $l->date_created, $l->inventory->item_name ?? '—', $l->movement_type,
                    $l->quantity, $l->previous_stock, $l->new_stock,
                    $l->receiver, $l->requester_name, $l->notes,
                ];
            }

            return [
                'header' => ['Date', 'Item', 'Type', 'Qty', 'Previous', 'New', 'Received By', 'Requester', 'Notes'],
                'data' => $data,
                'footer' => [],
            ];
        }

        return parent::rowsFor($request, $type);
    }
}
