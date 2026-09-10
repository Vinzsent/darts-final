<?php

namespace App\Http\Controllers;

use App\Models\Aircon;
use App\Models\PropertyInventory;
use App\Models\PropertyStockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PropertyReportController extends Controller
{
    public function index()
    {
        $categories = PropertyInventory::distinct()->orderBy('category')->pluck('category')->filter()->values();
        $brands = Aircon::distinct()->orderBy('brand')->pluck('brand')->filter()->values();
        $airconStatuses = Aircon::distinct()->orderBy('status')->pluck('status')->filter()->values();

        return view('property-reports.index', [
            'mode' => $this->mode(),
            'tabs' => $this->tabs(),
            'previewUrl' => route('property-reports.preview'),
            'exportUrl' => route('property-reports.export'),
            'categories' => $categories,
            'brands' => $brands,
            'airconStatuses' => $airconStatuses,
        ]);
    }

    protected function mode(): string
    {
        return 'property';
    }

    protected function tabs(): array
    {
        return [
            'inventory' => ['Property Inventory', 'fa-solid fa-couch'],
            'logs' => ['Stock Movement Logs', 'fa-solid fa-right-left'],
            'aircon' => ['Aircon Report', 'fa-solid fa-fan'],
        ];
    }

    public function preview(Request $request)
    {
        $type = $request->get('report_type', 'inventory');
        abort_unless(in_array($type, ['inventory', 'logs', 'aircon']), 400);

        $items = $this->queryFor($request, $type)->paginate(10);
        $totals = $this->totalsFor($request, $type);

        return view('property-reports._preview',
            ['items' => $items, 'type' => $type, 'totals' => $totals, 'generated' => now(), 'mode' => $this->mode()])->render();
    }

    public function export(Request $request)
    {
        $type = $request->get('report_type', 'inventory');
        abort_unless(in_array($type, ['inventory', 'logs', 'aircon']), 400);
        $format = $request->get('format', 'csv');
        abort_unless(in_array($format, ['csv', 'pdf']), 400);

        if ($format === 'pdf') {
            $items = $this->queryFor($request, $type)->get();
            $totals = $this->totalsFor($request, $type);

            return view('property-reports.print',
                ['items' => $items, 'type' => $type, 'totals' => $totals, 'generated' => now(), 'mode' => $this->mode()]);
        }

        $name = $this->mode() . '-' . str_replace('_', '-', $type) . '-report-' . now()->format('Ymd-His') . '.csv';
        $rows = $this->rowsFor($request, $type);

        return Response::stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $rows['header']);
            foreach ($rows['data'] as $r) { fputcsv($out, $r); }
            if (!empty($rows['footer'])) { fputcsv($out, $rows['footer']); }
            fclose($out);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $name . '"',
        ]);
    }
    private function queryFor(Request $request, string $type)
    {
        if ($type === 'inventory') {
            $q = PropertyInventory::query();

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
        if ($type === 'logs') {
            $q = PropertyStockLog::with('property')->orderBy('date_created', 'desc');

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
                        ->orWhereHas('property', function ($p) use ($search) {
                            $p->where('item_name', 'like', "%{$search}%");
                        });
                });
            }

            return $q;
        }

        $q = Aircon::with('maintenance');

        $search = trim((string) $request->get('search'));
        if ($search !== '') {
            $q->where(function ($q) use ($search) {
                $q->where('item_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $brand = trim((string) $request->get('brand'));
        if ($brand !== '') { $q->where('brand', $brand); }

        $status = trim((string) $request->get('aircon_status'));
        if ($status !== '') { $q->where('status', $status); }

        return $q->orderBy('aircon_id');
    }

    private function totalsFor(Request $request, string $type): array
    {
        if ($type === 'inventory') {
            $items = $this->queryFor($request, $type)->get();
            $valuation = 0.0;
            foreach ($items as $i) { $valuation += (float) $i->current_stock * (float) $i->unit_cost; }

            return ['valuation' => $valuation];
        }

        return [];
    }
    private function rowsFor(Request $request, string $type): array
    {
        if ($type === 'inventory') {
            $items = $this->queryFor($request, $type)->get();
            $valuation = 0.0;
            $data = [];
            foreach ($items as $i) {
                $line = (float) $i->current_stock * (float) $i->unit_cost;
                $valuation += $line;
                $data[] = [
                    $i->item_name, $i->category, $i->current_stock, $i->reorder_level,
                    $i->unit, $i->brand, $i->type, $i->status,
                    number_format((float) $i->unit_cost, 2), number_format($line, 2),
                ];
            }

            return [
                'header' => ['Item Name', 'Category', 'Stock', 'Reorder Lvl', 'Unit', 'Brand', 'Type', 'Status', 'Unit Cost', 'Total Value'],
                'data' => $data,
                'footer' => ['', '', '', '', '', '', '', '', 'Total Valuation:', number_format($valuation, 2)],
            ];
        }

        if ($type === 'logs') {
            $data = [];
            foreach ($this->queryFor($request, $type)->get() as $l) {
                $data[] = [
                    $l->date_created, $l->property->item_name ?? '—', $l->movement_type,
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

        $data = [];
        foreach ($this->queryFor($request, $type)->get() as $a) {
            $last = $a->maintenance->sortByDesc('service_date')->first();
            $next = $a->maintenance->sortByDesc('next_scheduled_date')->first();
            $data[] = [
                $a->item_number, $a->brand, $a->model, $a->capacity, $a->serial_number,
                $a->location, $a->area, $a->status,
                $last->service_date ?? '', $next->next_scheduled_date ?? '',
                number_format((float) $a->purchase_price, 2),
            ];
        }

        return [
            'header' => ['Item No.', 'Brand', 'Model', 'Capacity', 'Serial No.', 'Location', 'Area', 'Status', 'Last Service', 'Next Service', 'Price'],
            'data' => $data,
            'footer' => [],
        ];
    }
}
