<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ ['inventory' => ($mode === 'supply' ? 'Supply Inventory Report' : 'Property Inventory Report'), 'logs' => ($mode === 'supply' ? 'Supply Stock Movement Logs' : 'Stock Movement Logs'), 'aircon' => 'Aircon Report'][$type] }}</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; margin: 32px; color: #1f2937; }
    .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #065f46; padding-bottom: 12px; margin-bottom: 20px; }
    h1 { font-size: 20px; margin: 0; color: #065f46; }
    .sub { font-size: 11px; color: #6b7280; margin-top: 4px; }
    table { border-collapse: collapse; width: 100%; font-size: 11px; }
    th { background: #065f46; color: #fff; text-align: left; padding: 7px 8px; text-transform: uppercase; font-size: 9.5px; letter-spacing: .03em; }
    td { border-bottom: 1px solid #e5e7eb; padding: 6px 8px; }
    tr:nth-child(even) td { background: #f8faf9; }
    .right { text-align: right; }
    .center { text-align: center; }
    .footer-row td { background: #ecfdf5 !important; font-weight: 700; border-top: 2px solid #065f46; }
    .meta { font-size: 10px; color: #6b7280; margin-top: 24px; display: flex; justify-content: space-between; }
    @media print { body { margin: 12mm; } }
</style>
</head>
<body>
    <div class="header">
        <div>
            <h1>{{ ['inventory' => ($mode === 'supply' ? 'Supply Inventory Report' : 'Property Inventory Report'), 'logs' => ($mode === 'supply' ? 'Supply Stock Movement Logs' : 'Stock Movement Logs'), 'aircon' => 'Aircon Report'][$type] }}</h1>
            <div class="sub">DARTS — {{ $mode === 'supply' ? 'Supply In-charge' : 'Property Custodian' }} · Generated {{ $generated->format('F j, Y g:i A') }}</div>
        </div>
    </div>

    <table>
        <thead><tr>
@if($type === 'inventory')
            <th>Item Name</th><th>Category</th><th>Stock</th><th>Reorder</th><th>Unit</th><th>Brand</th><th>Type</th><th>Status</th><th class="right">Unit Cost</th><th class="right">Total Value</th>
@elseif($type === 'logs')
            <th>Date</th><th>Item</th><th>Type</th><th class="right">Qty</th><th class="center">Prev → New</th><th>Received By</th><th>Notes</th>
@else
            <th>Item No.</th><th>Brand / Model</th><th>Capacity</th><th>Serial No.</th><th>Location</th><th>Status</th><th>Last Service</th><th>Next Service</th><th class="right">Price</th>
@endif
        </tr></thead>
        <tbody>
@foreach($items as $i)
            <tr>
@if($type === 'inventory')
                <td>{{ $i->item_name }}</td><td>{{ $i->category }}</td><td>{{ $i->current_stock }}</td><td>{{ $i->reorder_level }}</td><td>{{ $i->unit }}</td><td>{{ $i->brand }}</td><td>{{ $i->type }}</td><td>{{ $i->status }}</td><td class="right">₱{{ number_format((float) $i->unit_cost, 2) }}</td><td class="right">₱{{ number_format((float) $i->current_stock * (float) $i->unit_cost, 2) }}</td>
@elseif($type === 'logs')
                <td>{{ $i->date_created }}</td><td>{{ $mode === 'supply' ? ($i->inventory->item_name ?? '—') : ($i->property->item_name ?? '—') }}</td><td>{{ $i->movement_type }}</td><td class="right">{{ $i->quantity }}</td><td class="center">{{ $i->previous_stock }} → {{ $i->new_stock }}</td><td>{{ $i->receiver }}</td><td>{{ $i->notes }}</td>
@else
                <td>{{ $i->item_number }}</td><td>{{ $i->brand }} {{ $i->model }}</td><td>{{ $i->capacity }}</td><td>{{ $i->serial_number }}</td><td>{{ $i->location }}</td><td>{{ $i->status }}</td><td>{{ $i->maintenance->sortByDesc('service_date')->first()->service_date ?? '—' }}</td><td>{{ $i->maintenance->sortByDesc('next_scheduled_date')->first()->next_scheduled_date ?? '—' }}</td><td class="right">₱{{ number_format((float) $i->purchase_price, 2) }}</td>
@endif
            </tr>
@endforeach
@if($type === 'inventory')
            <tr class="footer-row"><td colspan="9" class="right">Total Valuation:</td><td class="right">₱{{ number_format($totals['valuation'] ?? 0, 2) }}</td></tr>
@endif
        </tbody>
    </table>

    <div class="meta">
        <span>Total records: {{ $items->count() }}</span>
        <span>Prepared by: ______________________</span>
        <span>Approved by: ______________________</span>
    </div>

    <script>window.onload = function () { window.print(); };</script>
</body>
</html>
