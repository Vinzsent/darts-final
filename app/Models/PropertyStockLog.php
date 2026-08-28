<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyStockLog extends Model
{
    protected $table = 'property_stock_logs';
    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'inventory_id', 'request_id', 'movement_type', 'requester_name',
        'quantity', 'previous_stock', 'new_stock', 'notes',
        'created_by', 'date_created', 'receiver',
    ];

    public function property()
    {
        return $this->belongsTo(PropertyInventory::class, 'inventory_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}