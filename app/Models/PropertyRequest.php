<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    protected $table = 'property_request';
    protected $primaryKey = 'property_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'date_requested', 'date_return', 'temporary_transfer', 'permanent_transfer',
        'reason_for_transfer', 'category', 'item_name', 'request_description', 'brand',
        'color', 'type', 'quantity_requested', 'request_type', 'tagging', 'status',
        'qrcode', 'department_unit',
        'noted_by', 'noted_date', 'checked_by', 'checked_date',
        'verified_by', 'verified_date', 'issued_by', 'issued_date',
        'approved_by', 'approved_date', 'remarks',
    ];

    protected $casts = [
        'noted_date' => 'datetime',
        'checked_date' => 'datetime',
        'verified_date' => 'datetime',
        'approved_date' => 'datetime',
        'issued_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function noter()
    {
        return $this->belongsTo(User::class, 'noted_by', 'id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
