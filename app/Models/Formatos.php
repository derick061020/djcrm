<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formatos extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'data' => 'json',
    ];
}
