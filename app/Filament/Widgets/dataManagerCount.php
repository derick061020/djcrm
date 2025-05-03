<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Clientes;
use Carbon\Carbon;

class dataManagerCount extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public function getColumns(): int 
    {
        return 2;
    }

    public static function canView(): bool
    {
        return auth()->user()->hasRole('data_manager');
    }

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $approvedThisMonth = Clientes::where('estado', true)
            ->whereBetween('aprobado_at', [$startOfMonth, $endOfMonth])
            ->count();

        $pendingApproval = Clientes::where('estado', false)
            ->count();

        return [
            Stat::make('Aprobados este mes', $approvedThisMonth)
                ->description('Clientes aprobados en el mes actual')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
            Stat::make('Pendientes de Aprobación', $pendingApproval)
                ->description('Clientes que aún no han sido aprobados')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
