<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = [
        'name',
        'content',
        'language',
        'is_active',
        'template_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
