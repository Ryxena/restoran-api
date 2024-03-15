<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TotalProductOverview extends BaseWidget
{
    protected static ?int $sort = -2;
    protected int | string | array $columnSpan = 'full';
    protected function getStats(): array
    {
        $orders = Order::where('status', 'paid')->get();

        $products = [];
        foreach ($orders as $order) {
            $most_freq = OrderDetail::where('order_id', $order->id)->select(['product_id', 'product_count'])->first();
            array_push($products, $most_freq);
        }

        $arr = [];
        foreach ($products as $product) {
            array_push($arr, ['product_id' => $product['product_id'],'product_count'=>$product['product_count']]);
        }

        $totals = [];
        foreach ($arr as $item) {
            $product_id = $item['product_id'];
            $product_count = $item['product_count'];
            $totals[$product_id] = isset($totals[$product_id]) ? $totals[$product_id] + $product_count : $product_count;
        }
        $id_most_product = array_search(max($totals), $totals);
        $most_product = Product::where('id', $id_most_product)->first();

        $date = DB::table('order_details')
            ->whereDate('updated_at', Carbon::now())
            ->count()
            ;
        return [
            Stat::make('Total Product', Product::count())
                ->color('success')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Produk paling laris', $most_product->name)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Transaksi Hari ini', $date)
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }
}
