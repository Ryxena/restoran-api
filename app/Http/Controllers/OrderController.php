<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
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
        $product = Product::where('id', $id);

        $order_check = Order::where('user_id', Auth::user()->id)->where('status', 'no-paid')->first();
        if (empty($order_check)) {
            $order = new Order();
            $order->user_id = Auth::user()->id;
            $order->date = Carbon::now();
            $order->status = 'no-paid';
            $order->total_price = 0;
            $order->save();
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
            $order_detail = new OrderDetail();
            $order_detail->product_count += $request->product_count;
            $order->detail->total_price += $product->price * $request->product_count;
            $order_detail->update();
        }

        return response()->json([
            'msg' => 'Berhasil masuk ke chart'
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
            $product = new Product();
            $product->stock -= $order_detail->product_count;
            $product->update();
        }

        return response()->json([
            'msg' => 'Berhasil Checkout'
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
