<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalProductOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Product', Product::count())
            ->description('Info product ygy')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Product', Product::count())
            ->description('Info product ygy')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Product', Product::count())
            ->description('Info product ygy')
            ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
