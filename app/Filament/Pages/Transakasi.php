<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\TransactionReport;

class Transakasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Bulanan';
    protected static string $view = 'filament.pages.transakasi';

    protected function getHeaderWidgets(): array
    {
        return [
            TransactionReport::class
        ];
    }
}
