<?php

namespace App\Http\Controllers\ApiControllers;

use App\Order;
use App\Product;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OrderController extends Controller
{
    public function userOrderIndex()
    {
        $orders = Order::with(['user', 'products'])->where('user_id', auth('api')->user()->id)->whereNotNull('paid_datetime')->latest()->get()->all();

        $output['result'] = 'success';
        $output['data'] = $orders;
        return response()->json($output);
    }

    public function cartIndex()
    {
        $items = Order::with(['products', 'user'])->where('user_id', auth('api')->user()->id)->whereNull('paid_datetime')->first();

        $output['result'] = 'success';
        $output['data'] = $items;
        return response()->json($output);
    }

    public function cartCheckout()
    {
        $cart = Order::where('user_id', auth('api')->user()->id)->whereNull('paid_datetime')->first();
        $items = $cart->products;

        foreach ($items as $item) {
            $cart->products()->syncWithoutDetaching([$item->id => ['unit_price' => $item->price, 'quantity' => $item->pivot->quantity]]);
        }

        $pay_code = Carbon::now()->timestamp;

        $cart->pay_code = $pay_code;
        $cart->paid_datetime = Carbon::now();

        $cart->update();

        $bankaccount_number = Setting::all()->firstWhere('setting_key', 'bankacount_number')->setting_value;

        $output['result'] = 'success';
        $output['data'] = compact('pay_code', 'bankaccount_number');
        return response()->json($output);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $output['result'] = 'success';
        $output['data'] = $order;
        $output['data']['user'] = $order->user;
        $output['data']['products'] = $order->products;
        return response()->json($output);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Order $order
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, Order $order, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|min:1|integer',
            'product_id' => 'required|min:1|integer',
        ]);

        $order->products()->find($product->id)->pivot->update(['quantity' => intval($validatedData['quantity'])]);

        $output['result'] = 'success';
        $output['data'] = $order;
        $output['data']['user'] = $order->user;
        $output['data']['products'] = $order->products;
        return response()->json($output);
    }

    public function updateTransactionCode(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'transaction_code' => 'required|min:2|numeric|unique:orders,transaction_code',
        ]);
        $order->transaction_code = $validatedData['transaction_code'];
        $order->update();

        $output['result'] = 'success';
        $output['data'] = 'کد پرداخت با موفقیت ثبت شد!';
        return response()->json($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();

        $output['result'] = 'success';
        $output['data'] = 'سفارش ' . $order->pay_code . ' با موفقیت حذف شد!';

        return response()->json($output);
    }

    public function cartProductDestroy(Order $order, Product $product)
    {
        $order->products()->detach($product->id);
        if ($order->products()->count() == 0) {
            $order->delete();
        }

        $output['result'] = 'success';
        $output['data'] = 'محصول "' . $product->name . '" با موفقیت از سفارش "' . $order->pay_code . '" حذف شد!';
        return response()->json($output);
    }
}
