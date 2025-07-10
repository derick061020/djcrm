<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\Stats;
use App\Filament\Widgets\dataManagerCount;
use App\Filament\Widgets\RecentActivityWidget;
use App\Filament\Widgets\GeneralStatsWidget;
use App\Filament\Widgets\AnalyticsPieChartWidget;

class StatisticsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.statistics-page';
    protected static ?string $navigationGroup = 'Estadísticas';
    protected static ?string $navigationLabel = 'Estadísticas Generales';

    protected function getHeaderWidgets(): array
    {
        return [
            GeneralStatsWidget::class,
            Stats::class,
            AnalyticsPieChartWidget::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            RecentActivityWidget::class,
            dataManagerCount::class,
            AnalyticsPieChartWidget::class,
        ];
    }
}
