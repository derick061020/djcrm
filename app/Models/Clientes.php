<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Clientes extends Model
{
    protected $table = 'clientes';
    
    protected $guarded = [];


    protected $casts = [
        'fecha_estimada' => 'datetime',
        'horas_trabajo' => 'array',
        'requerimientos' => 'array',
        'referencias_musicales' => 'array',
        'aprobado' => 'boolean',
        'hora_inicio' => 'string',
        'hora_fin' => 'string',
        'budget_items' => 'json',
        'seguimiento' => 'json',
    ];

    public function agendadoPor()
    {
        return $this->belongsTo(User::class, 'agendado_por_id');
    }

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por_id');
    }
}
