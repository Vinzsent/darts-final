<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canvass extends Model
{
    protected $table = 'canvass';
    protected $primaryKey = 'canvass_id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'canvass_date', 'total_amount', 'status', 'canvassed_by', 'notes', 'hide_canvass', 'created_by',
    ];

    protected $casts = [
        'canvass_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(CanvassItem::class, 'canvass_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function canvassedBy()
    {
        return $this->belongsTo(User::class, 'canvassed_by');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'Canvassed');
    }
}