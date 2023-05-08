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
            ->select('products.*', 'categories.*', 'users.name AS vendor_name', 'carts.id AS cart_id', 'carts.cart_quantity AS cart_quantity')
            ->where('cart_userid', '=', $userId)
            ->orderBy('carts.id')
            ->get();

        $totalPrice = $carts->sum(function ($cart) {
            return $cart->cart_quantity * $cart->product_price;
        });

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

        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Failed to add to cart! Quantity must be 1+.');
        }

        $cartItem = Cart::where('cart_productid', $request->input('product_id'))
                        ->where('cart_userid', $request->input('userid'))
                        ->first();

        if ($cartItem) {
            $cartItem->cart_quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = new Cart([
                'cart_userid' => $request->input('userid'),
                'cart_productid' => $request->input('product_id'),
                'cart_categoryid' => $request->input('categ_id'),
                'cart_quantity' => $quantity,
                'cart_price' => $request->input('product_price')
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
