<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircon extends Model
{
    protected $table = 'aircons';
    protected $primaryKey = 'aircon_id';

    protected $fillable = [
        'item_number', 'category', 'brand', 'model', 'type', 'quantity',
        'capacity', 'serial_number', 'location', 'status', 'purchase_date',
        'warranty_expiry', 'last_service_date', 'maintenance_schedule',
        'installation_date', 'energy_efficiency_rating', 'power_consumption',
        'notes', 'purchase_price', 'depreciated_value', 'receiver',
        'supplier_id', 'created_by', 'date_created', 'date_updated',
        'campus', 'area', 'item_no', 'serial_no', 'bldg', 'supplier_info',
    ];

    public function images()
    {
        return $this->hasMany(AirconImage::class, 'aircon_id', 'aircon_id');
    }

    public function maintenance()
    {
        return $this->hasMany(AirconMaintenance::class, 'aircon_id', 'aircon_id');
    }
}
