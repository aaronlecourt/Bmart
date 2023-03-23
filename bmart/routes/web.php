<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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

Auth::routes();

//Buyer Route List
Route::middleware(['auth', 'user-access:buyer'])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

//Seller Route List
Route::middleware(['auth', 'user-access:vendor'])->group(function(){
    Route::get('/vendor/home',[HomeController::class, 'vendorHome']);
    Route::get('/vendor/home', [ProductController::class, 'index'])->name('vendor.home');
    Route::resource('/vendor/products', ProductController::class);
});
