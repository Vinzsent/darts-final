<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'purchase_order_items';
    protected $primaryKey = 'poi_id';

    protected $fillable = [
        'po_id', 'item_number', 'item_description', 'quantity', 'unit_cost', 'line_total',
    ];

    public function order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }
}