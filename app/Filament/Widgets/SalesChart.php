<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class SalesChart extends ChartWidget
{
    // Widget Heading
    protected static ?string $heading = 'Sales Data';

    protected static ?int $sort = 2;

    // Method to provide sales data
    public function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Sales (Units)',
                    'data' => [200, 250, 300, 270, 350, 400, 450],
                    'backgroundColor' => '#FF9800',
                    'borderColor' => '#FF9800',
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
        return 'Displays sales trends over the past months.';
    }
}
