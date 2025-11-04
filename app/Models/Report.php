<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_day',
        'to_day',
        'generated_at',
        'created_by_admin_id',
        'summary_rows',
        'included_survey_ids',
        'order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'from_day' => 'date',
            'to_day' => 'date',
            'generated_at' => 'datetime',
            'summary_rows' => 'array',
            'included_survey_ids' => 'array',
            'active' => 'boolean',
        ];
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function reportItems()
    {
        return $this->hasMany(ReportItem::class);
    }
}
