<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    protected $table = 'supplier_transaction';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'date_received', 'invoice_no', 'sales_type', 'category', 'item_description',
        'brand', 'type', 'color', 'quantity', 'unit', 'unit_price', 'amount',
        'supplier_id', 'status', 'created_by', 'date_created',
    ];

    public function supplier() { return $this->belongsTo(Supplier::class, 'supplier_id'); }
}
