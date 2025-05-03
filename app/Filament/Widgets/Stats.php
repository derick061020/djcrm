<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Clientes; // Agregar el modelo Cliente

class Stats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = now();
        
        // Obtener el mes actual
        $mesActual = $today->month;
        $anioActual = $today->year;
        
        // Calcular fechas del mes actual
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        // Calcular fechas de la semana
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        // Eventos del mes (mostrando todos los eventos del mes completo)
        $eventosMes = Clientes::whereBetween('fecha_estimada', [$startOfMonth, $endOfMonth])
            ->where('agendado', 1)
            ->count();

        // Eventos del día
        $eventosDia = Clientes::whereDate('fecha_estimada', $today)
            ->where('agendado', 1)
            ->count();

        // Eventos de la semana (mostrando todos los eventos de la semana completa)
        $eventosSemana = Clientes::whereBetween('fecha_estimada', [$startOfWeek, $endOfWeek])
            ->where('agendado', 1)
            ->count();

        // Eventos Exitosos Realizados
        $eventosExitosos = Clientes::where('estado', 'exitoso')
            ->where('agendado', 1)
            ->count();

        return [
            Stat::make('Eventos del mes', $eventosMes)
                ->description('Eventos programados en el mes completo')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary')
                ->icon('heroicon-o-calendar'),

            Stat::make('Eventos del día', $eventosDia)
                ->description('Eventos programados hoy')
                ->descriptionIcon('heroicon-o-clock')
                ->color('success')
                ->icon('heroicon-o-clock'),

            Stat::make('Eventos de la semana', $eventosSemana)
                ->description('Eventos programados en la semana completa')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('warning')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Eventos Exitosos Realizados', $eventosExitosos)
                ->description('Eventos completados con éxito')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }
}
