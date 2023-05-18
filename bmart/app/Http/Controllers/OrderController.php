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
            ->orderBy('created_at', 'desc')
            ->get();
    
        $list = OrderItem::where('cancel_status','=',0)->orwhere('cancel_status','=',null)->count();
        // dd($list);
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
    
                $product = $orderItem->product;
    
                $orderItemInfo = new \stdClass();
                $orderItemInfo->id = $orderItem->id;
                $orderItemInfo->order_id = $order->id;
                $orderItemInfo->product_name = $product ? $product->product_name : 'Product Not Found';
                $orderItemInfo->product_price = $orderItem->product_price;
                $orderItemInfo->vendor_id = $orderItem->vendor_id;
                $orderItemInfo->quantity = $orderItem->quantity;
                $orderItemInfo->status = $orderItem->status;
                $orderItemInfo->cancel_status = $orderItem->cancel_status;
                $orderItemInfo->address = $order->address;
                $orderItemInfo->order_price = $orderItem->product_price * $orderItem->quantity;
                $orderItemInfo->product_id = $orderItem->product_id;
                $orderItemInfo->vendor_id = $orderItem->vendor_id;
    
                $vendorOrder->order_items->push($orderItemInfo);
            }
        }
    
        return view('orders', compact('vendorOrders','list'));
    }
    
        
    
    // public function index()
    // {
    // $userId = auth()->id(); 
    //     // Retrieve all orders made by the logged in user with eager loading for orders and order items
    //     $orders = Order::with('items.product')
    //     ->where('user_id', $userId)
    //     ->orderBy('created_at')
    //     ->get();
    
    // // Separate orders by order and order item
    // $ordersByOrder = collect();
    // foreach ($orders as $order) {
    //     foreach ($order->items as $orderItem) {
    //         $orderId = $order->id;
    //         $orderItemsByOrder = $ordersByOrder->where('order_id', $orderId)->first();
    //         if (!$orderItemsByOrder) {
    //             $orderItemsByOrder = new \stdClass();
    //             $orderItemsByOrder->order_id = $orderId;
    //             $orderItemsByOrder->vendor_name = $orderItem->vendor->name;
    //             $orderItemsByOrder->address = $order->address;
    //             $orderItemsByOrder->total_price = 0;
    //             $orderItemsByOrder->status = $orderItem->status;
    //             $orderItemsByOrder->items = collect();
    //             $ordersByOrder->push($orderItemsByOrder);
    //         }
    //         $product = $orderItem->product_id == $orderItem->product->id ? $orderItem->product : null;
    //         $orderItemInfo = new \stdClass();
    //         $orderItemInfo->product_name = $product ? $product->product_name : 'Product Not Found';
    //         $orderItemInfo->product_price = $orderItem->product_price;
    //         $orderItemInfo->quantity = $orderItem->quantity;
    //         $orderItemInfo->order_price = $orderItem->product_price * $orderItem->quantity;
    //         $orderItemsByOrder->status = $orderItem->status;
    //         $orderItemsByOrder->items->push($orderItemInfo);
    //         $orderItemsByOrder->total_price += $orderItemInfo->order_price;
    //     }
    // }

    // Sort the order items within each order by order ID
//     $ordersByOrder->transform(function ($orderItemsByOrder) {
//         $orderItemsByOrder->items = $orderItemsByOrder->items->sortBy('product_name');
//         return $orderItemsByOrder;
//     });
    
//     return view('orders', compact('ordersByOrder'));
// }

// public function vendorOrders()
// {
//     $vendorId = auth()->id();
    
//     $orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
//     ->join('products', 'order_items.product_id', '=', 'products.id')
//     ->join('users', 'orders.user_id', '=', 'users.id')
//     ->where('order_items.vendor_id', $vendorId)
//     ->select('orders.*', 'order_items.*','products.*')
//     ->with('items','user')
//     ->get()
//     ->groupBy('order_id');
     
//     return view('vendorOrders', compact('orders'));
// }

public function vendorOrders()
{
    $vendorId = auth()->id();
    
    $orders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->where('order_items.vendor_id', $vendorId)
        ->orderBy('orders.created_at','desc')
        ->select('orders.*', 'order_items.*','products.product_name', 'products.description')
        ->with('items', 'user')
        ->get()
        ->groupBy('order_id')
        ->map(function ($items) {
            $items->each(function ($item) {
                $item->finalPrice = $item->product_price * $item->quantity;
            });
            $items->totalPrice = $items->sum('finalPrice');
            // dd($items->totalPrice);
            return $items;
        });
        $list = OrderItem::where('cancel_status','=',0)->orwhere('cancel_status','=',null)->count();

        // dd($totalPrice);
    
    return view('vendorOrders', compact('orders','list'));
}

    public function confirm(Request $request)
    {
        
        $action = $request->input('action');
        $orderId = $request->input('order_id');
        $vendorId = auth()->user()->id;
        
        $orderItems = OrderItem::where('order_id', $orderId)
                                ->whereHas('product', function($query) use ($vendorId){
                                    $query->where('vendor_id', $vendorId);
                                })
                                ->get();
        
                                // dd($action);
        if($action == null){
            foreach ($orderItems as $item) {
                $productId = $item->product_id;
                $product = Product::find($productId);
                $quantity = $item->quantity;
                $product->quantity -= $quantity;
                $product->save();
                $item->status = 'confirmed';
                $item->save();
            }
            return redirect()->back()->with('message','Order was successfully confirmed!');
        }
        
        else if($action == 'markShipped'){
            foreach ($orderItems as $item) {
                $item->status = 'shipped';
                $item->save();
            }
            return redirect()->back()->with('message',"Order was marked as 'Shipped'!");
        }

        else if($action == 'markCancelled'){
            foreach ($orderItems as $item) {
                $productId = $item->product_id;
                $product = Product::find($productId);
                $quantity = $item->quantity;
                $product->quantity -= $quantity;
                $product->save();
                $item->status = 'cancelled';
                $item->save();
            }
            return redirect()->back()->with('message',"Order was marked as 'Cancelled'!");
        }

        else if($action == 'markRejected'){
            foreach ($orderItems as $item) {
                $item->status = 'request rejected';
                $item->save();
            }
            return redirect()->back()->with('message',"Successfully rejected cancel request!");
        }
        else if($action == 'markDelivered'){
            foreach ($orderItems as $item) {
                $item->status = 'delivered';
                $item->save();
            }
            return redirect()->back()->with('message',"Order was marked as 'Delivered'!");
        }
        else{

        }
        return redirect()->back();
    }

    // public function deliver(Request $request){
    //     $orderItems = OrderItem::where('order_id', $orderId)
    //                             ->whereHas('product', function($query) use ($vendorId){
    //                                 $query->where('vendor_id', $vendorId);
    //                             })
    //                             ->get();
    //                             foreach ($orderItems as $item) {
    //                                 $item->status = 'delivered';
    //                                 $item->save();
    //                             }    
    //     return redirect()->route('orders.vendor');
    // }

    // public function confirm(Request $request)
    // {
    //     $orderId = $request->input('order_id');

    //     dd($orderId);
    //     $order = Order::findOrFail($id);
        
    //     // Update the status of the order to "confirmed"
    //     $order->status = 'confirmed';
    //     $order->save();
        
    //     // Loop through each item in the order
    //     foreach ($order->orderItems as $item) {
    //         $product = $item->product;
            
    //         // Subtract the quantity of the item from the product's quantity
    //         $product->quantity -= $item->quantity;
    //         $product->save();
    //     }
        
    //     return redirect()->back()->with('message', 'Order confirmed!');
    // }

    public function cancel(Request $request)
    {
        $cancel_req = $request->input('cancelReason');
        $cancel_stat = $request->input('cancel_status');
        $productIds = $request->input('product_ids');
        $orderId = $request->input('order_id');
        $vendorId = $request->input('vendor_id');
        // $orderItems = $request->input('order_items');

        // $vendorIds = OrderItem::whereIn('product_id', $productIds)->pluck('vendor_id');

        // dd($productIds,$vendorId);
        if ($orderId) {
            // Retrieve the order items based on the selected product_ids and order_id
            $orderItems = OrderItem::whereIn('product_id', $productIds)
                ->where('order_id', $orderId)
                ->where('vendor_id',$vendorId)
                ->get();
    
            // $vendorId = $orderItems->pluck('vendor_id')->unique(); // Get the unique vendor IDs from the order items
    
             // This will display the unique vendor IDs
            foreach ($orderItems as $orderItem) {
                // Update the cancel_req and cancel_status columns
                $orderItem->cancel_req = $cancel_req;
                $orderItem->cancel_status = $cancel_stat;
                $orderItem->status = 'for approval';
    
                // Save the changes to the database
                $orderItem->save();
            }
        }
    
        return redirect()->back()->with('message','Cancellation request was successfully sent!');
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
    $action = $request->input('action');
    $order = $request->input('order_id');
    $vendorId = $request->input('vendor_id');
    // dd($vendorId);
    // $item = OrderItem::where('order_id', $order)->first();
    $orderItems = OrderItem::where('order_id', $order)
                                ->whereHas('product', function($query) use ($vendorId){
                                    $query->where('vendor_id', $vendorId);
                                })
                                ->get();

    // dd($orderItems);
    // dd($action, $order);
    if($action == 'removeList'){
        foreach($orderItems as $item){
            $item->cancel_status = 1;
            $item->save();
        }
    }
    if($action == 'cancelReq'){
        foreach ($orderItems as $item) {
            $item->status = 'pending';
            $item->save();
        }
    }
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
        $orderItem->status = 'pending';
        $orderItem->cancel_status = 0;


        $orderItem->save();

        // Remove the cart item
        $cart->delete();
    }

    return redirect()->route('orders.index', compact('order','orderItem'));
    }
}

}
