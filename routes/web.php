<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::group(['middleware' => ['admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::resource('products', 'ProductController');

    Route::put('setting/{setting}/update', 'SettingController@update')->name('setting.update');

    Route::get('orders', 'OrderController@adminOrderIndex')->name('orders.index');
    Route::delete('orders{order}/destroy', 'OrderController@destroy')->name('orders.destroy');
    Route::get('orders/{order}', 'OrderController@adminShow')->name('orders.new_show');
    Route::put('orders/{order}/edit-verified', 'OrderController@updateVerified')->name('orders.update.verified');
});

Route::get('/', 'ProductController@userIndex')->name('home');
Route::get('/home', 'ProductController@userIndex')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('orders', 'OrderController@userOrderIndex')->name('user.orders.index');
    Route::get('orders/{order}', 'OrderController@show')->name('orders.show');
    Route::delete('orders/{order}/destroy', 'OrderController@destroy')->name('orders.destroy');
    Route::put('orders/{order}/edit-transaction-code', 'OrderController@updateTransactionCode')->name('orders.update.transactionCode');

    Route::get('cart', 'OrderController@cartIndex')->name('cart.index');
    Route::get('cart/checkout', 'OrderController@cartCheckout')->name('cart.checkout');
    Route::put('cart/{order}/product/{product}update', 'OrderController@updateQuantity')->name('cart.update');
    Route::delete('cart/{order}/product/{product}/destroy', 'OrderController@cartProductDestroy')->name('cart.destroy');

    Route::get('add-to-cart/{product}', 'ProductController@addToCart')->name('add-to-cart');
});
