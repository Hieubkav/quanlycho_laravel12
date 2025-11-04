<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_id',
        'sale_id',
        'survey_day',
        'notes',
        'active',
        'order',
    ];

    protected function casts(): array
    {
        return [
            'survey_day' => 'date',
            'active' => 'boolean',
        ];
    }

    public function market()
    {
        return $this->belongsTo(Market::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function surveyItems()
    {
        return $this->hasMany(SurveyItem::class);
    }
}
