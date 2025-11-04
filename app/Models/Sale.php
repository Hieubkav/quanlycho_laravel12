<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Sale extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'address',
        'notes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function markets()
    {
        return $this->belongsToMany(Market::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
