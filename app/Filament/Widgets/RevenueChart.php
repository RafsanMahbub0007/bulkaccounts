<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    // Widget Heading
    protected static ?string $heading = 'Revenue Data';

    protected static ?int $sort = 3;

    // Method to provide revenue data
    public function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => [5000, 6000, 7500, 7000, 8500, 9500, 11000],
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#4CAF50',
                    'fill' => true,
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        ];
    }

    // Set chart type to 'line'
    protected function getType(): string
    {
        return 'line';
    }

    // Optional description for the chart
    public function getDescription(): ?string
    {
        return 'Displays revenue trends over the past months.';
    }
}
