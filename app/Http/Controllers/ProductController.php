<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->get()->all();
        return view('admin.products.index', compact('products'));
    }

    public function userIndex()
    {
        $products = Product::latest()->get()->all();
        return view('user.products.index', compact('products'));
    }

    public function addToCart(Product $product)
    {
        $cart = Order::where('user_id', Auth::user()->id)->whereNull('paid_datetime')->first();
        if ($cart != null) {
            if ($cart->products()->find($product->id) != null)
                $cart->products()->find($product->id)->pivot->update(['quantity' => $cart->products()->find($product->id)->pivot->quantity + 1]);
        } else {
            $cart = new Order();
            $cart->user_id = Auth::user()->id;
            $cart->save();
        }

        $cart->products()->syncWithoutDetaching([$product->id => ['unit_price' => $product->price]]);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.products.insert');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'required|file',
            'price' => 'required|numeric|min:1',
        ]);

        $imageName = Carbon::now()->format('Ymd His') . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $imageName);
        $validatedData['image'] = 'images/' . $imageName;

        $product = Product::create($validatedData);

        return redirect(route('admin.products.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'image' => 'nullable|file',
            'price' => 'required|numeric',
        ]);

        if (array_key_exists('image', $validatedData)) {
            $imageName = Carbon::now()->format('Ymd His') . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $imageName);
            $validatedData['image'] = 'images/' . $imageName;
        }

        $product->update($validatedData);

        return redirect(route('admin.products.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        File::delete($product->image);
        $product->delete();
        return redirect(route('admin.products.index'));
    }
}
