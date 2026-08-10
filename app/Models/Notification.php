<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'related_id', 'related_type',
        'is_read', 'created_at',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function scopeUnread($q) { return $q->where('is_read', false); }
}
