<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockLog;
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
        ];
    }

    public function index()
    {
        return view('property-reports.index', [
            'mode' => $this->mode(),
            'tabs' => $this->tabs(),
            'previewUrl' => route('supply-reports.preview'),
            'exportUrl' => route('supply-reports.export'),
            'categories' => collect(),
            'brands' => collect(),
            'airconStatuses' => collect(),
        ]);
    }

    public function queryFor(Request $request, string $type)
    {
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

    public function rowsFor(Request $request, string $type): array
    {
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
