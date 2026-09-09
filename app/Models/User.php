<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'suffix',
        'academic_title',
        'department',
        'user_type',
        'username',
        'password',
        'profile',
        'status',
        'position',
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

    public function getProfileUrlAttribute(): ?string
    {
        if ($this->profile && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->profile)) {
            return asset('storage/' . $this->profile);
        }
        return null;
    }

    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name ?? 'U', 0, 1) . substr($this->last_name ?? 'S', 0, 1));
    }
}
