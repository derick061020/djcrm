<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Filament\Resources\EventResource;
use App\Models\Clientes;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 1;
    public function fetchEvents(array $fetchInfo): array
    {
        return Clientes::query()
            ->where('fecha_estimada', '>=', $fetchInfo['start'])
            ->where('agendado', '=', true)
            //->where('date', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Clientes $event) => [
                    'title' => $event->tipo_evento,
                    'start' => $event->fecha_estimada,
                    'url' => '/crm/clientes/'.$event->id.'/edit',
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }
}