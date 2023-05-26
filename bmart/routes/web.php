<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SaleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::post('/logout', [CartController::class, 'logout'])->name('logout');

Auth::routes();

//Buyer Route List
Route::middleware(['auth', 'user-access:buyer'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/cart', CartController::class);
    Route::match(['get', 'post'], '/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    // Create new order
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    // Route::post('/orders', [OrderController::class, 'confirm'])->name('orders.confirm');

    });

//Seller Route List
Route::middleware(['auth', 'user-access:vendor'])->group(function(){
    Route::get('/vendor/home',[HomeController::class, 'vendorHome']);
    Route::get('/vendor/home', [ProductController::class, 'index'])->name('vendor.home');
    Route::resource('/vendor/products', ProductController::class);
    Route::get('/vendor/profile', [UserController::class,'changeProfile'])->name('change-profile');
    Route::post('/vendor/profile', [UserController::class,'updateProfile'])->name('update-profile');
    Route::resource('/vendor/categories', CategoryController::class);

    Route::get('/vendor/orders', [OrderController::class, 'vendorOrders'])->name('orders.vendor');
    Route::post('/vendor/orders', [OrderController::class, 'confirm'])->name('orders.confirm');
    // Route::post('/vendor/orders', [OrderController::class, 'deliver'])->name('orders.deliver');
    // Route::post('/vendor/sales', [SaleController::class, 'index'])->name('vendor.sales');
    Route::match(['get', 'post'], '/vendor/sales', [SaleController::class, 'index'])->name('vendor.sales');
    Route::match(['get', 'post'],'/vendor/sales/clear-filters', [SaleController::class, 'clearFilters'])->name('vendor.sales.clear-filters');


});
