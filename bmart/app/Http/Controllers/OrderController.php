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
                $productId = $item->product_id;
                $product = Product::find($productId);
                $quantity = $item->quantity;
                $product->quantity -= $quantity;
                $product->save();
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

    public function cancel(Request $request)
    {
        $cancel_req = $request->input('cancelReason');
        $cancel_stat = $request->input('cancel_status');
        $productIds = $request->input('product_ids');
        $orderId = $request->input('order_id');
        $vendorId = $request->input('vendor_id');

        // dd($productIds,$vendorId);
        if ($orderId) {
            // Retrieve the order items based on the selected product_ids and order_id
            $orderItems = OrderItem::whereIn('product_id', $productIds)
                ->where('order_id', $orderId)
                ->where('vendor_id',$vendorId)
                ->get();
        
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

    public function store(Request $request)
    {
        // dd($request);
        $action = $request->input('action');
        $order = $request->input('order_id');
        $vendorId = $request->input('vendor_id');
        $orderItems = OrderItem::where('order_id', $order)
                                    ->whereHas('product', function($query) use ($vendorId){
                                        $query->where('vendor_id', $vendorId);
                                    })
                                    ->get();

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
