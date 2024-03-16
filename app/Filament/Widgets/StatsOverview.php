<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $data_permonth = Trend::query(Order::where('status', 'paid'))
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
            )
            ->dateAlias('alias')
        ->perMonth()
        ->average('total_price');
        $resultmonth = $data_permonth->map(fn (TrendValue $value) => number_format($value->aggregate, 0, '.', ''))->implode(', ');
        $data_perday = Trend::query(Order::where('status', 'paid'))
        ->between(
            start: now()->startOfDay(),
            end: now()->startOfDay(),
            )
            ->dateAlias('alias')
        ->perMonth()
        ->average('total_price');
        $resultday = $data_perday->map(fn (TrendValue $value) => number_format($value->aggregate, 0, '.', ''))->implode(', ');
        $isprofitmonth = ($resultmonth >= 100000) ? 'Untung' : 'Rugi';
        $isprofitday = ($resultday >= 100000) ? 'Untung' : 'Rugi';
        return [
            Stat::make('Pendapat rata rata bulanan', 'Rp ' . $resultmonth)
                ->description('Pendapat rata rata bulanan ' . $isprofitmonth),
            Stat::make('Pendapatan perhari', $resultday)
                ->description('Rata rata pendapat perhari ' . $isprofitday)
        ];
    }
}
