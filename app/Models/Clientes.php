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
    public static function getCategorias(): array
{
    return [
        'Pedir requisitos por llamada',
        'Crear presupuesto',
        'Presupuesto creado',
        'Presupuesto Presentado',
        'Primer follow up',
        'Segundo follow up',
        'Tercer follow up',
        'Follow up correo',
        'Eventos pospuestos'
    ];
}
public function getCategoriaAttribute($value)
{
    return $value;
}
public function setCategoriaAttribute($value)
{
    $categorias = self::getCategorias();
    if (!in_array($value, $categorias)) {
        throw new \InvalidArgumentException('Categoría inválida');
    }
    $this->attributes['categoria'] = $value;
}

    public function aprobadoPor()
    {
        return $this->belongsTo(User::class, 'aprobado_por_id');
    }
}
