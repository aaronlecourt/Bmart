<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = Auth::id();
        $user = Auth::user();

        $carts = DB::table('carts')
            ->join('categories', 'cart_categoryid', '=', 'categories.id')
            ->join('products', 'cart_productid', '=', 'products.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->select('products.*', 'categories.*', 'users.name AS vendor_name', 'users.id AS vendor_id','carts.id AS cart_id', 'carts.cart_quantity AS cart_quantity')
            ->where('cart_userid', '=', $userId)
            ->orderBy('carts.id')
            ->get();

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->cart_quantity * $cart->product_price;
        });

        // Delete any cart item that has a product_id that is not in the Product table
        $cartItemsToDelete = DB::table('carts')->whereNotIn('cart_productid', Product::pluck('id')->toArray())->delete();
        
        return view('cart', compact('carts', 'totalPrice', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $quantity = $request->input('quantity');
        $productId = $request->input('product_id');
        $userId = $request->input('userid');
        
        // Check if product exists in the Product table
        $product = Product::find($productId);
        if (!$product) {
            // If product doesn't exist, delete it from the cart
            Cart::where('cart_productid', $productId)
                ->where('cart_userid', $userId)
                ->delete();
            return redirect()->back()->with('error', 'The selected product is no longer available.');
        }
        
        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be greater than 0.');
        }
        
        $cartItem = Cart::where('cart_productid', $productId)
                        ->where('cart_userid', $userId)
                        ->first();
    
        if ($cartItem) {
            $cartItem->cart_quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = new Cart([
                'cart_userid' => $userId,
                'cart_productid' => $productId,
                'cart_categoryid' => $request->input('categ_id'),
                'cart_quantity' => $quantity,
                'cart_price' => $product->product_price
            ]);
            $cartItem->save();
        }
        
        return redirect()->back()->with('message', 'Successfully added to cart!');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if ($request->input('quantity') <= 0) {
            return redirect()->back()->with('error', 'The quantity must be greater than 0.');
        }
    
        // Update cart table with new quantity value
        $cart = Cart::find($id);
        $cart->cart_quantity = $request->input('quantity');
        $cart->save();
    
        return redirect()->route('cart.index')->with('message', 'Item updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();
            return redirect()->back()->with('message', 'Item deleted successfully!');
        } else {
            return redirect()->back()->with('error', 'Item not found!');
        }
    }

    public function clear()
    {
        $userId = Auth::id();
        Cart::where('cart_userid', $userId)->delete();
        return redirect()->route('cart.index')->with('message', 'Cart cleared successfully');
    }
    
}
