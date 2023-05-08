<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Cart;
use Auth;

class OrderController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $orders = Order::where('user_id', $userId)->paginate(5);
        return view('orders', compact('orders'));
    }


    // public function show($orderId)
    // {
    //     $ord = Order::findOrFail($orderId);
    //     $products = $order->products;

    //     return view('orders', compact('ord', 'products'));
    // }

    public function store(Request $request)
{
    // Validate the form data
    $validatedData = $request->validate([
        'name' => 'required',
        'address' => 'required',
        'city' => 'required',
        'country' => 'required',
        'postalcode' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
    ]);

    $userId =  Auth::id();
    // Create a new order
    $order = new Order;
    $order->user_id = $userId;
    $order->name = $request->name;
    $order->address = $request->address;
    $order->city = $request->city;
    $order->country = $request->country;
    $order->postalcode = $request->postalcode;
    $order->phone = $request->phone;
    $order->email = $request->email;
    $order->total_price = $request->totalprice;
    $order->status = 'pending';
    $order->save();

    // Attach products to the order
    $carts = Cart::where('cart_userid', Auth::id())->get();
    foreach ($carts as $cart) {
        $product = Product::findOrFail($cart->cart_productid);
        $order->products()->attach($product->id, ['quantity' => $cart->cart_quantity, 'price' => $product->product_price]);
        $cart->delete();
    }

    return redirect()->route('orders.index', ['orderId' => $order->id]);
}

}
