<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    protected $table = 'supply_request';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'date_requested', 'item_number', 'date_needed', 'department_unit',
        'purpose', 'sales_type', 'category', 'item_name', 'request_description',
        'brand', 'color', 'size', 'type', 'unit_cost', 'total_cost', 'unit',
        'quantity_requested', 'quality_issued', 'amount', 'request_type',
        'noted_by', 'noted_date', 'checked_by', 'checked_date',
        'verified_by', 'verified_date', 'issued_by', 'issued_date',
        'approved_by', 'approved_date', 'remarks', 'status', 'semester', 'school_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function noter()
    {
        return $this->belongsTo(User::class, 'noted_by');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department_unit', $department);
    }
}
