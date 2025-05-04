<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Whatsapp extends Model
{
    //
    protected $guarded = [];
    protected $casts = [
        'mensajes' => 'array'
    ];
}
