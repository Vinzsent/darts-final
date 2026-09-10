<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirconMaintenance extends Model
{
    protected $table = 'aircon_maintenance';
    protected $primaryKey = 'maintenance_id';

    protected $fillable = [
        'aircon_id', 'service_date', 'service_type', 'technician',
        'next_scheduled_date', 'remarks', 'created_by', 'date_created',
    ];

    public function aircon()
    {
        return $this->belongsTo(Aircon::class, 'aircon_id', 'aircon_id');
    }
}
