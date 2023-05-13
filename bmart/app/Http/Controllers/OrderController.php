<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Auth;

class OrderController extends Controller
{    
    public function index()
    {
        $userId = auth()->id();
        
        // Retrieve all orders made by the logged in user with eager loading for orders and order items
        $orders = Order::with('items.product')
            ->where('user_id', $userId)
            ->get();
        
        // Separate orders by vendor and order item
        $vendorOrders = collect();
        foreach ($orders as $order) {
            foreach ($order->items as $orderItem) {
                $vendorId = $orderItem->vendor_id;
                $vendor = User::where('id', $vendorId)->first();
                $vendorOrder = $vendorOrders->where('vendor_id', $vendorId)->first();
                if (!$vendorOrder) {
                    $vendorOrder = new \stdClass();
                    $vendorOrder->vendor_id = $vendorId;
                    $vendorOrder->vendor_name = $vendor->name;
                    $vendorOrder->order_items = collect();
                    $vendorOrders->push($vendorOrder);
                }
                $product = $orderItem->product_id == $orderItem->product->id ? $orderItem->product : null;
                $orderItemInfo = new \stdClass();
                $orderItemInfo->order_id = $order->id;
                $orderItemInfo->product_name = $product ? $product->product_name : 'Product Not Found';
                $orderItemInfo->product_price = $orderItem->product_price;
                $orderItemInfo->quantity = $orderItem->quantity;
                $orderItemInfo->status = $order->status;
                $orderItemInfo->address = $order->address;
                $orderItemInfo->order_price = $orderItem->product_price * $orderItem->quantity;
                $vendorOrder->order_items->push($orderItemInfo);
            }
        }

        // Sort the order items within each vendor order by order ID
        $vendorOrders->transform(function ($vendorOrder) {
            $vendorOrder->order_items = $vendorOrder->order_items->sortBy('order_id');
            return $vendorOrder;
        });
        
        return view('orders', compact('vendorOrders'));
    }
     
    public function vendorOrders()
    {
        $vendorId = auth()->id();
        $productIds = DB::table('products')
            ->where('user_id', $vendorId)
            ->pluck('id');
        $orderIds = DB::table('order_items')
            ->whereIn('product_id', $productIds)
            ->pluck('order_id')
            ->unique();
        $orders = Order::whereIn('id', $orderIds)->with('items.product')->get();
        
        foreach ($orders as $order) {
            $totalPrice = 0;
            foreach ($order->items as $item) {
                $totalPrice += $item->quantity * $item->price;
            }
            $order->totalPrice = $totalPrice;
        }

        return view('vendorOrders', compact('orders'));
    }

    public function cancel()
    {
        return redirect()->back();
    }


    // public function show($orderId)
    // {
    //     $ord = Order::findOrFail($orderId);
    //     $products = $order->products;

    //     return view('orders', compact('ord', 'products'));
    // }

//     public function store(Request $request)
// {
//     // Validate the form data
//     $validatedData = $request->validate([
//         'name' => 'required',
//         'address' => 'required',
//         'city' => 'required',
//         'country' => 'required',
//         'postalcode' => 'required',
//         'phone' => 'required',
//         'email' => 'required|email',
//     ]);

//     $userId = Auth::id();
//     // Update the user data
//     $user = Auth::user();
//     $user->name = $request->input('name');
//     $user->address = $request->input('address');
//     $user->city = $request->input('city');
//     $user->country = $request->input('country');
//     $user->postalcode = $request->input('postalcode');
//     $user->number = $request->input('phone');
//     $user->save();

//     // Create a new order
//     $order = new Order;
//     $order->user_id = $userId;
//     $order->name = $request->input('name');
//     $order->address = $request->input('address');
//     $order->city = $request->input('city');
//     $order->country = $request->input('country');
//     $order->postalcode = $request->input('postalcode');
//     $order->phone = $request->input('phone');
//     $order->email = $request->input('email');
//     $order->total_price = $request->input('totalprice');
//     $order->status = 'pending';
//     $order->save();

//     // Attach products to the order
//     $carts = Cart::where('cart_userid', Auth::id())->get();
//     foreach ($carts as $cart) {
//         $product = Product::findOrFail($cart->cart_productid);
//         $order->products()->attach($product->id, ['quantity' => $cart->cart_quantity, 'price' => $product->product_price]);
//         $cart->delete();
//     }

//     return redirect()->route('orders.index', ['orderId' => $order->id]);

//     }
public function store(Request $request)
{
    // dd($request);
    // Validate the form input
    $validated = $request->validate([
        'user_id' => 'required',
        'name' => 'required|string',
        'address' => 'required|string',
        'city' => 'required|string',
        'country' => 'required|string',
        'postalcode' => 'required|string',
        'phone' => 'required|string',
        'email' => 'required|email',
        // 'totalprice' => 'required|numeric',
    ]);

    if(!$validated){
        return redirect()->back()->withErrors(['error' => 'Fill in the required fields before placing order!']);
    }else{

    // Update user record with entered details
    $user = User::findOrFail($validated['user_id']);
    $user->name = $validated['name'];
    $user->city = $validated['city'];
    $user->address = $validated['address'];
    $user->country = $validated['country'];
    $user->postalcode = $validated['postalcode'];
    $user->number = $validated['phone'];
    $user->save();

        // Create a new order
    $order = new Order();
    $order->user_id = $validated['user_id'];
    $order->name = $validated['name'];
    $order->address = $validated['address'];
    $order->city = $validated['city'];
    $order->country = $validated['country'];
    $order->postal_code = $validated['postalcode'];
    $order->phone_number = $validated['phone'];
    $order->email = $validated['email'];
    $order->status = 'pending';
    $order->save();

    // Add each cart item as an order item
    $carts = Cart::where('cart_userid', auth()->id())->get();

    foreach ($carts as $cart) {
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->product_id = $cart->cart_productid;
        $orderItem->vendor_id = $cart->cart_vendorid;
        $orderItem->quantity = $cart->cart_quantity;
        $orderItem->product_price = $cart->cart_price;

        $orderItem->save();

        // Remove the cart item
        $cart->delete();
    }

    return redirect()->route('orders.index', compact('order','orderItem'));
    }
}

}
