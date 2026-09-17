<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $table = 'school_year';
    protected $primaryKey = 'shoo_year_id';
    public $timestamps = false;

    protected $fillable = [
        'school_year_name',
        'current_year',
    ];
}
