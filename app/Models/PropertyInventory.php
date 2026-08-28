<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyInventory extends Model
{
    protected $table = 'property_inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'item_name', 'brand', 'size', 'color', 'type', 'category', 'description',
        'current_stock', 'quantity', 'unit', 'unit_cost', 'reorder_level',
        'supplier_id', 'location', 'receiver', 'status', 'received_notes',
        'created_by', 'date_created', 'last_updated_by', 'date_updated',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function stockLogs()
    {
        return $this->hasMany(PropertyStockLog::class, 'inventory_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'Active');
    }
}