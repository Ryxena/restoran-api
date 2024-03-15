<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TransactionReport extends ChartWidget
{
    protected static ?string $heading = 'Transaksi bulan ini';
    
    protected function getData(): array
    {
        $data = Trend::query(Order::where('status', 'paid'))
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->dateAlias('dates')
        ->perMonth()
        ->count();
    return [
        'datasets' => [
            [
                'label' => 'Transaksi Bulan',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
