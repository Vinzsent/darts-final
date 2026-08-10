<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'supplier_id';
    public $timestamps = false;

    protected $fillable = [
        'supplier_name', 'contact_person', 'contact_number', 'email_address',
        'fax_number', 'website', 'address', 'city', 'province', 'zip_code',
        'country', 'business_type', 'category', 'payment_terms',
        'tax_identification_number', 'status', 'landline_number',
        'created_by', 'date_created', 'last_updated_by', 'date_updated', 'notes',
    ];

    public function inventory() { return $this->hasMany(Inventory::class, 'supplier_id'); }
    public function procurements() { return $this->hasMany(Procurement::class, 'supplier_id'); }
    public function transactions() { return $this->hasMany(SupplierTransaction::class, 'supplier_id'); }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([$this->address, $this->city, $this->province, $this->zip_code]));
    }
}
