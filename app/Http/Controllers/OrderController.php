<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminOrderIndex()
    {
        $orders = Order::with(['products', 'user'])->whereNotNull('paid_datetime')->latest()->get()->all();
        return view('admin.orders.index', compact('orders'));
    }

    public function userOrderIndex()
    {
        $orders = Auth::user()->orders()->whereNotNull('paid_datetime')->latest()->get()->all();
        return view('user.orders.index', compact('orders'));
    }

    public function cartIndex()
    {
        $items = Order::with(['products', 'user'])->where('user_id', Auth::user()->id)->whereNull('paid_datetime')->first();
        return view('user.cart.index', compact('items'));
    }

    public function cartCheckout()
    {
        $cart = Order::where('user_id', Auth::user()->id)->whereNull('paid_datetime')->first();
        $items = $cart->products;

        foreach ($items as $item) {
            $cart->products()->syncWithoutDetaching([$item->id => ['unit_price' => $item->price, 'quantity' => $item->pivot->quantity]]);
        }

        $pay_code = Carbon::now()->timestamp;

        $cart->pay_code = $pay_code;
        $cart->paid_datetime = Carbon::now();

        $cart->update();

        $bankaccount_number = Setting::all()->firstWhere('setting_key', 'bankacount_number')->setting_value;

        return redirect()->back()->with(compact('pay_code', 'bankaccount_number'));

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('user.orders.show', compact('order'));
    }

    public function adminShow(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
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

        return redirect(route('cart.index'));
    }

    public function updateTransactionCode(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'transaction_code' => 'required|min:2|numeric|unique:orders,transaction_code',
        ]);
        $order->transaction_code = $validatedData['transaction_code'];
        $order->update();
        return redirect(route('orders.show', $order))->with(['success' => 'کد پرداخت با موفقیت ثبت شد!']);
    }

    public function updateVerified(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'verified' => 'required',
        ]);

        $order->verified = $validatedData['verified'];
        $order->update();
        $s = ($validatedData['verified'] == 1) ? 'شد!' : 'نشد!';
        return redirect(route('admin.orders.new_show', $order))->with(['success' => 'سفارش با موفقیت تایید ' . $s]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with(['success' => 'سفارش ' . $order->pay_code . ' با موفقیت حذف شد!']);
    }

    public function cartProductDestroy(Order $order, Product $product)
    {
        $order->products()->detach($product->id);
        if ($order->products()->count() == 0) {
            $order->delete();
        }
        return redirect()->back();
    }
}
