<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['api'], 'prefix' => 'auth'], function () {
    Route::post('login', 'ApiControllers\AuthController@login');
    Route::post('logout', 'ApiControllers\AuthController@logout');
    Route::post('refresh', 'ApiControllers\AuthController@refresh');
    Route::post('me', 'ApiControllers\AuthController@me');
});

Route::get('/', 'ApiControllers\ProductController@userIndex')->name('home');
Route::get('/home', 'ApiControllers\ProductController@userIndex')->name('home');

Route::group(['middleware' => ['api', 'auth:api']], function () {
    Route::get('orders', 'ApiControllers\OrderController@userOrderIndex')->name('user.orders.index');
    Route::get('orders/{order}', 'ApiControllers\OrderController@show')->name('orders.show');
    Route::delete('orders/{order}/destroy', 'ApiControllers\OrderController@destroy')->name('orders.destroy');
    Route::put('orders/{order}/edit-transaction-code', 'ApiControllers\OrderController@updateTransactionCode')->name('orders.update.transactionCode');

    Route::get('cart', 'ApiControllers\OrderController@cartIndex')->name('cart.index');
    Route::get('cart/checkout', 'ApiControllers\OrderController@cartCheckout')->name('cart.checkout');
    Route::put('cart/{order}/product/{product}update', 'ApiControllers\OrderController@updateQuantity')->name('cart.update');
    Route::delete('cart/{order}/product/{product}/destroy', 'ApiControllers\OrderController@cartProductDestroy')->name('cart.destroy');

    Route::get('add-to-cart/{product}', 'ApiControllers\ProductController@addToCart')->name('add-to-cart');
});
