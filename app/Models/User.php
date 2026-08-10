<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'title', 'first_name', 'middle_name', 'last_name', 'email', 'suffix',
        'academic_title', 'department', 'user_type', 'username', 'password', 'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function username(): string
    {
        return 'username';
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    public function getDisplayNameAttribute(): string
    {
        $name = trim("{$this->first_name} {$this->last_name}");
        if ($this->suffix) $name .= ", {$this->suffix}";
        return $name;
    }
}
