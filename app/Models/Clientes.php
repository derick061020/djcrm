<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;

class Clientes extends Model
{
    protected $table = 'clientes';
    
    protected $guarded = [];

    protected $fillable = [
        'nombre',
        'contacto',
        'tipo_evento',
        'fecha_estimada',
        'cantidad_personas',
        'horas_trabajo',
        'aprobado',
        'hora_inicio',
        'hora_fin',
        'ubicacion_local',
        'budget_items',
        'seguimiento',
        'analytics',
        'overall_satisfaction',
        'service_quality',
        'product_quality',
        'survey_comments',
        'would_recommend',
        'survey_completed_at'
    ];

    protected $casts = [
        'fecha_estimada' => 'datetime',
        'horas_trabajo' => 'array',
        'aprobado' => 'boolean',
        'hora_inicio' => 'string',
        'hora_fin' => 'string',
        'budget_items' => 'json',
        'seguimiento' => 'json',
        'analytics' => 'json',
        'overall_satisfaction' => 'integer',
        'service_quality' => 'integer',
        'product_quality' => 'integer',
        'would_recommend' => 'boolean',
        'survey_completed_at' => 'datetime',
    ];

    public function agendadoPor()
    {
        return $this->belongsTo(User::class, 'agendado_por_id');
    }

    public function satisfactionSurvey()
    {
        return $this->hasOne(SatisfactionSurvey::class);
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
