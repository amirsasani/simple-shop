<?php

namespace App\Http\Controllers\ApiControllers;

use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    public function userIndex()
    {
        $products = Product::latest()->get()->all();

        $output['result'] = 'success';
        $output['data'] = $products;
        return response()->json($output);
    }

    public function addToCart(Product $product)
    {
        $cart = Order::where('user_id', auth('api')->user()->id)->whereNull('paid_datetime')->first();
        if ($cart != null) {
            if ($cart->products()->find($product->id) != null)
                $cart->products()->find($product->id)->pivot->update(['quantity' => $cart->products()->find($product->id)->pivot->quantity + 1]);
        } else {
            $cart = new Order();
            $cart->user_id = auth('api')->user()->id;
            $cart->save();
        }

        $cart->products()->syncWithoutDetaching([$product->id => ['unit_price' => $product->price]]);

        $output['result'] = 'success';
        $output['data'] = $cart;
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $output['result'] = 'success';
        $output['data'] = $product;
        return response()->json($output);
    }
}
