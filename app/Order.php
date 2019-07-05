<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product')->withPivot(['unit_price', 'quantity'])->withTimestamps();
    }

    public function orderTotalPrice()
    {
        $total = 0;
        $order_products = $this->products;
        foreach ($order_products as $item) {
            $total += $item->pivot->quantity * $item->pivot->unit_price;
        }
        return $total;
    }

    public function orderTotalProductsCount()
    {
        $total = 0;
        $order_products = $this->products;
        foreach ($order_products as $item) {
            $total += $item->pivot->quantity;
        }
        return $total;
    }

    public static function cardTotalPrice()
    {
        $total = 0;
        $items = Order::where('user_id', Auth::user()->id)->whereNull('paid_datetime')->first();
        if ($items) {
            foreach ($items->products as $item) {
                $total += $item->pivot->quantity * $item->pivot->unit_price;
            }
        }
        return $total;
    }

    public static function userCartTotalProductsCount()
    {
        $total = 0;
        $items = Order::where('user_id', Auth::user()->id)->whereNull('paid_datetime')->first();
        if ($items) {
            foreach ($items->products as $item) {
                $total += $item->pivot->quantity;
            }
        }
        return $total;
    }
}
