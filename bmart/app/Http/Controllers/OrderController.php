<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
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
        // Validate the form data
        $request->validate([
            'username' => 'required',
            'address' => 'required',
            'city' => 'required',
            'country' => 'required',
            'postalcode' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        // Create a new order in the database
        $order = Order::create([
            'username' => $request->input('username'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'postalcode' => $request->input('postalcode'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'total_price' => $totalPrice, // Set this variable to the total price of the order
        ]);

        // Add the items ordered to the order
        foreach($carts as $cart) {
            $order->items()->create([
                'product_name' => $cart->product_name,
                'quantity' => $cart->cart_quantity,
                'price' => $cart->product_price,
            ]);
        }

        // Redirect the user to a confirmation page
        return redirect()->route('orders.confirmation', ['order' => $order]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
