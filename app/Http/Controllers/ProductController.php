<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        $most_freq = OrderDetail::select(['product_id', 'product_count'])->get();
        $arr = [];
        foreach ($most_freq as $item) {
            // $freq_order = array_count_values()
            array_push($arr, ['product_id' => $item->product_id, 'product_count' => $item->product_count]);
        }

        $totals = [];
        foreach ($arr as $item) {
            $product_id = $item['product_id'];
            $product_count = $item['product_count'];
            $totals[$product_id] = isset($totals[$product_id]) ? $totals[$product_id] + $product_count : $product_count;
        }
        // dd($totals);

        $id_most_product = array_search(max($totals), $totals);

        // dd($id_most_product);
        $most_product = Product::where('id', $id_most_product)->first();
        return response()->json([
            'msg' => 'Ambil product paling sering dibeli',
            'data' => $most_product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //
    }
}
