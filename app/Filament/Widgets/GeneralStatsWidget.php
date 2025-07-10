<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Clientes;
use App\Models\Formatos;
use App\Models\User;

class GeneralStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Clientes Totales', Clientes::count())
                ->description('Total de clientes registrados')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('success'),
            
            Stat::make('Formatos Totales', Formatos::count())
                ->description('Total de formatos')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('info'),
            
            Stat::make('Usuarios Activos', User::count())
                ->description('Usuarios activos en el sistema')
                ->descriptionIcon('heroicon-o-user')
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    protected function getTitle(): string
    {
        return 'Estadísticas Generales';
    }
}