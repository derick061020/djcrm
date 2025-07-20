<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Clientes;
use Illuminate\Database\Query\Builder;
use Filament\Support\Colors\Color;

class AnalyticsPieChartWidget extends ChartWidget
{
    protected static ?int $sort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        return 'Análisis de Interacción de Clientes';
    }

    public function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $analyticsData = Clientes::whereNotNull('analytics')
            ->get()
            ->map(function ($cliente) {
                $analytics = $cliente->analytics;
                
                return [
                    'duration' => $analytics['duration'] ?? 0,
                    'scrolls' => $analytics['scrolls'] ?? 0,
                    'clicks' => $analytics['clicks'] ?? 0,
                    'messages_sent' => $analytics['messages_sent'] ?? 0,
                    'messages_received' => $analytics['messages_received'] ?? 0,
                ];
            });

        // Calculate totals
        $totalDuration = $analyticsData->sum('duration');
        $totalScrolls = $analyticsData->sum('scrolls');
        $totalClicks = $analyticsData->sum('clicks');
        $totalMessagesSent = $analyticsData->sum('messages_sent');
        $totalMessagesReceived = $analyticsData->sum('messages_received');

        // Calculate percentages
        $totalActions = $totalDuration + $totalScrolls + $totalClicks + $totalMessagesSent + $totalMessagesReceived;

        $data = [
            'labels' => [
                'Tiempo Total',
                'Scrolls',
                'Clicks',
                'Mensajes Enviados',
                'Mensajes Recibidos',
            ],
            'datasets' => [
                [
                    'data' => $totalActions !== 0 ? [
                        ($totalDuration / $totalActions) * 100,
                        ($totalScrolls / $totalActions) * 100,
                        ($totalClicks / $totalActions) * 100,
                        ($totalMessagesSent / $totalActions) * 100,
                        ($totalMessagesReceived / $totalActions) * 100,
                    ] : [
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                    'backgroundColor' => [
                        '#4F46E5', // Indigo
                        '#10B981', // Emerald
                        '#F59E0B', // Amber
                        '#EC4899', // Pink
                        '#14B8A6', // Teal
                    ],
                ],
            ],
        ];

        return $data;
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'font' => [
                            'size' => 12,
                            'family' => 'sans-serif',
                            'weight' => 'bold',
                        ],
                        'padding' => 10,
                        'usePointStyle' => true,
                    ],
                ],
            ],
            'layout' => [
                'padding' => [
                    'top' => 20,
                    'right' => 20,
                    'bottom' => 40,
                    'left' => 20,
                ],
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
            'elements' => [
                'arc' => [
                    'borderWidth' => 2,
                    'borderColor' => '#fff',
                ],
            ],
            'cutout' => '60%',
            'circumference' => 360,
            'rotation' => 0,
            'center' => [
                'text' => 'Interacciones',
                'color' => '#6B7280',
                'font' => [
                    'size' => 20,
                    'family' => 'sans-serif',
                    'weight' => 'bold',
                ],
            ],
        ];
    }
}
