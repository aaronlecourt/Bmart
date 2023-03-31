<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $products = DB::table('products')
                    ->join('categories','products.category_id', '=', 'categories.id')
                    ->select('products.*','categories.*','products.id AS prod_id')
                    ->where('user_id','=',$userId)
                    ->paginate(5);
        return view('vendorHome',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('products_create', compact('products','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validation
        $validate = $request->validate([
            'product_name'=>['required','regex:/^[a-zA-Z0-9 ]*$/','max:50'],
            'product_price'=>'required',
            'category_id'=>'required',
            'quantity'=>'required',
            'description'=>'required',
        ]);
        Product::create($request->all());
        return redirect()->route('vendor.home')->with('message','You have successfully added a product!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // $product = 
        // return view('vendorHome',compact('products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products_edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //validation
        $validate = $request->validate([
            'product_name'=>['required','regex:/^[a-zA-Z]+$/u','max:50'],
            'product_price'=>'required',
            'category_id'=>'required',
            'quantity'=>'required',
            'description'=>'required',
        ]);
        $product->update($request->all());
        return redirect()->route('vendor.home')->with('message', 'You have successfully edited the product!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('vendor.home')->with('message', 'You have successfully deleted the product!');
    }
}
