<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    protected $table = 'procurement';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = [
        'date_received', 'invoice_no', 'sales_type', 'category', 'supplier_id',
        'item_description', 'brand', 'type', 'color', 'quantity', 'unit',
        'unit_price', 'amount', 'status',
    ];

    public function supplier() { return $this->belongsTo(Supplier::class, 'supplier_id'); }
}
