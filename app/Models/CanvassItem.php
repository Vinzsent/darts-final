<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CanvassItem extends Model
{
    protected $table = 'canvass_items';
    protected $primaryKey = 'canvass_item_id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'canvass_id', 'item_number', 'supplier_name', 'department', 'campus',
        'item_description', 'quantity', 'unit_cost', 'total_cost',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function canvass()
    {
        return $this->belongsTo(Canvass::class, 'canvass_id');
    }
}