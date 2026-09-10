<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AirconImage extends Model
{
    protected $table = 'aircon_images';
    protected $primaryKey = 'id';

    protected $fillable = ['aircon_id', 'image_path', 'created_at'];

    public function aircon()
    {
        return $this->belongsTo(Aircon::class, 'aircon_id', 'aircon_id');
    }
}
