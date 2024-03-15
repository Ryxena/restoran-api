<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\TransactionReport;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $product = Product::where('id', $id)->first();

        $order_check = Order::where('user_id', Auth::user()->id)->where('status', 'no-paid')->first();
        if (empty($order_check)) {
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->date = Carbon::now();
            $order->status = 'no-paid';
            $order->total_price = $product->price * $request->product_count;
            $order->save();
        } else {
            $order_check->total_price += $product->price * $request->product_count;
            $order_check->update();
        }

        $new_order = Order::where('user_id', Auth::user()->id)->where('status', 'no-paid')->first();

        $check_order_detail = OrderDetail::where('product_id', $product->id)->where('order_id', $new_order->id)->first();
        if (empty($check_order_detail)) {
            $order_detail = new OrderDetail();
            $order_detail->order_id = $new_order->id;
            $order_detail->product_id = $product->id;
            $order_detail->product_count = $request->product_count;
            $order_detail->total_price = $product->price * $request->product_count;
            $order_detail->save();
        } else {
            $check_order_detail->product_count += $request->product_count;
            $check_order_detail->total_price += $product->price * $request->product_count;
            $check_order_detail->update();
        }

        return response()->json([
            'msg' => 'Berhasil masuk ke cart'
        ]);
    }

    public function checkout(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();

        $order = Order::where('user_id', $user->id)->where('status', 'no-paid')->first();
        $order->status = 'paid';
        $order->update();

        $order_details = OrderDetail::where('order_id', $order->id)->get();

        foreach ($order_details as $order_detail) {
            $product = Product::where('id', $order_detail->product_id)->first();
            $product->stock -= $order_detail->product_count;
            $product->update();
        }

        return response()->json([
            'msg' => 'Berhasil Checkout'
        ]);
    }
    public function transaction_report_by_day(Request $request, $date)
    {
        // Mendapatkan tanggal awal dan akhir dari hari yang diinput
        $fullDate = Carbon::now()->format('Y-m') . '-' . $date;
        $startOfDay = Carbon::createFromFormat('Y-m-d', $fullDate)->startOfDay();
        $endOfDay = Carbon::createFromFormat('Y-m-d', $fullDate)->endOfDay();

        // Menghitung transaksi per hari dalam rentang bulan yang ditentukan
        $orders_per_day = Order::whereBetween('date', [$startOfDay, $endOfDay])
            ->where('status', 'paid')
            ->get();

        if (count($orders_per_day) > 0) {
            $amount = 0;
            foreach ($orders_per_day as $item) {
                $amount += $item->total_price;
            }

            // Memeriksa apakah laporan transaksi untuk bulan ini sudah ada atau belum
            $check_transaction = TransactionReport::where('date', '>=', $startOfDay)
                ->where('date', '<=', $endOfDay)
                ->first();

            if (empty($check_transaction)) {
                $datas = TransactionReport::create([
                    'date' => $fullDate,
                    'status' => $amount > 50000 ? 'profit' : 'loss',
                    'amount' => $amount
                ]);
            } else {
                $check_transaction->update([
                    'amount' => $amount,
                    'status' => $amount > 50000 ? 'profit' : 'loss'
                ]);

                return response()->json([
                    'msg' => 'Berhasil membuat laporan transaksi hari ini',
                    'data' => $check_transaction
                ]);
            }

            return response()->json([
                'msg' => 'Berhasil membuat laporan transaksi hari ini',
                'data' => $datas
            ]);
        }
        return response()->json([
            'msg' => 'Gagal membuat laporan transaksi hari ini',
            'error' => 'Gagal ambil data'
        ]);
    }

    public function transaction_report_by_month(Request $request, $month)
    {
        // Mendapatkan tanggal awal dan akhir dari bulan yang diinput
        $fullDate = Carbon::now()->format('Y-' . $month . '-d');
        $startOfMonth = Carbon::createFromFormat('m', $month)->startOfMonth();
        $endOfMonth = Carbon::createFromFormat('m', $month)->endOfMonth();

        // Menghitung transaksi per hari dalam rentang bulan yang ditentukan
        $orders_per_month = Order::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('status', 'paid')
            ->get();

        if (count($orders_per_month) > 0) {
            $totalAmount = 0;
            foreach ($orders_per_month as $order) {
                $totalAmount += $order->total_price;
            }

            // Memeriksa apakah laporan transaksi untuk bulan ini sudah ada atau belum
            $transactionReport = TransactionReport::where('date', '>=', $startOfMonth)
                ->where('date', '<=', $endOfMonth)
                ->first();

            if (empty($transactionReport)) {
                $transactionReport = TransactionReport::create([
                    'date' => $fullDate,
                    'status' => $totalAmount > 50000 ? 'profit' : 'loss',
                    'amount' => $totalAmount
                ]);
            } else {
                $transactionReport->update([
                    'amount' => $totalAmount,
                    'status' => $totalAmount > 50000 ? 'profit' : 'loss'
                ]);

                return response()->json([
                    'msg' => 'Berhasil memperbarui laporan transaksi untuk bulan ini',
                    'data' => $transactionReport
                ]);
            }

            return response()->json([
                'msg' => 'Berhasil membuat laporan transaksi untuk bulan ini',
                'data' => $transactionReport
            ]);
        }

        return response()->json([
            'msg' => 'Gagal membuat laporan transaksi untuk bulan ini',
            'error' => 'Tidak ada data transaksi pada bulan yang dimaksud'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
