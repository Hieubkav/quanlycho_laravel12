<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'notes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function sales()
    {
        return $this->belongsToMany(Sale::class, 'sale_market');
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
