<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $table = 'stock_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'inventory_id', 'request_id', 'movement_type', 'requester_name',
        'quantity', 'previous_stock', 'new_stock', 'status', 'notes',
        'created_by', 'date_created', 'receiver',
    ];

    public function inventory() { return $this->belongsTo(Inventory::class, 'inventory_id'); }
}
