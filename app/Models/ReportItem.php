<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'survey_id',
        'product_id',
        'price',
        'notes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
