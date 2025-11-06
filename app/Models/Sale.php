<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Sale extends Authenticatable implements FilamentUser
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
        return $this->belongsToMany(Market::class, 'sale_market');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->active && $panel->getId() === 'khaosat';
    }
}
