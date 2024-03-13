<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\TransactionReport;
use Illuminate\Http\Request;
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

    public function transaction_report(Request $request)
    {
        // hitung per hari
        $orders_per_day = Order::where('date', Carbon::now()->format('Y-m-d'))->where('status', 'paid')->get();
        // dd(Carbon::now()->format('Y-m-d'));
        if (count($orders_per_day) > 0) {
            $amount = 0;
            foreach ($orders_per_day as $item) {
                $amount += $item->total_price;
            }

            // cek dah ada atau belum
            $check_transaction = TransactionReport::where('date', Carbon::now()->format('Y-m-d'))->first();

            if (empty($check_transaction)) {
                $datas = TransactionReport::create([
                    'date' => Carbon::now(),
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
