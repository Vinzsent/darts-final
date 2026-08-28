<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'item_name', 'category', 'brand', 'color', 'size', 'type', 'description',
        'current_stock', 'quantity', 'unit', 'unit_cost', 'reorder_level',
        'supplier_id', 'location', 'receiver', 'status', 'received_notes',
        'created_by', 'date_created', 'last_updated_by', 'date_updated', 'qrcode',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function stockLogs()
    {
        return $this->hasMany(StockLog::class, 'inventory_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'Active');
    }

    public function scopeLowStock($q)
    {
        return $q->whereColumn('current_stock', '<=', 'reorder_level')
                 ->where('current_stock', '>', 0);
    }

    public function scopeOutOfStock($q)
    {
        return $q->where('current_stock', '<=', 0);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->reorder_level;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->current_stock <= 0) return 'out';
        if ($this->current_stock <= $this->reorder_level) return 'low';
        return 'ok';
    }
}