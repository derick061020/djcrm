<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatisfactionSurvey extends Model
{
    protected $fillable = [
        'client_id',
        'overall_satisfaction',
        'service_quality',
        'product_quality',
        'comments',
        'would_recommend'
    ];

    protected $casts = [
        'overall_satisfaction' => 'integer',
        'service_quality' => 'integer',
        'product_quality' => 'integer',
        'would_recommend' => 'boolean'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
