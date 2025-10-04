<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class StatsOverview extends BaseWidget
{


    protected static ?string $pollingInterval = '10s'; 

    protected static ?int $sort = 1;


    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->color('success') 
                ->icon('heroicon-o-cube')
                ->description('Total products available in the store')
                ->descriptionIcon('heroicon-m-cube')
                ->chart([120, 140, 130, 150, 170, 160, 180]), 

            Stat::make('Total Orders', Order::count())
                ->color('primary') 
                ->icon('heroicon-o-clipboard-document-list')
                ->description('Total orders placed by customers')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->chart([100, 120, 110, 140, 160, 180, 200]),

            Stat::make('Total Revenue', '$' . number_format(Payment::sum('amount'), 2))
                ->color('warning') 
                ->icon('heroicon-o-currency-dollar')
                ->description('Revenue from completed payments')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([2000, 2500, 2300, 2700, 3000, 3300, 3500]), 

            Stat::make('Delivered Orders', Delivery::where('status', 'delivered')->count())
                ->color('info')  
                ->icon('heroicon-o-truck')
                ->description('Orders that have been delivered successfully')
                ->descriptionIcon('heroicon-m-check-circle')
                ->chart([50, 60, 55, 70, 80, 85, 90]) 
        ];
    }
}
