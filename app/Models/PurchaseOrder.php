<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'po_id';
    protected $casts = [
        'po_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'po_number', 'po_date', 'supplier_name', 'supplier_address',
        'payment_method', 'payment_details', 'cash_amount', 'subtotal', 'total_amount',
        'status', 'prepared_by', 'checked_by', 'approved_by',
        'prepared_date', 'checked_date', 'approved_date', 'notes', 'created_by',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'po_id');
    }
}