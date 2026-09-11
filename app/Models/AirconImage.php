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

    public function getUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        // Legacy rows store pre-existing relative paths.
        if (str_starts_with($this->image_path, 'uploads/')) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }
}
