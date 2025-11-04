<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit_id',
        'is_default',
        'notes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
