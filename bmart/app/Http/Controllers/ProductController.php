<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Auth;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $userId = Auth::id();
    $query = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*','categories.*','products.id AS prod_id','products.created_at AS created_at','products.updated_at AS updated_at')
        ->where('products.user_id', $userId);

    // Check if search query is present in the request
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', '%'.$search.'%')
              ->orWhere('products.id', 'like', '%'.$search.'%')
              ->orWhere('product_price', 'like', '%'.$search.'%')
              ->orWhere('quantity', 'like', '%'.$search.'%')
              ->orWhere('description', 'like', '%'.$search.'%')
              ->orWhere('category_name', 'like', '%'.$search.'%');
            //   ->orWhereRaw("DATE_FORMAT(products.created_at, '%b %d, %Y') LIKE '%$search%'");
        });        
    }

    $products = $query->paginate(5);

    // Append search parameter to pagination links
    if ($request->has('search')) {
        $search = $request->input('search');
        $products->appends(['search' => $search]);
    }

    return view('vendorHome', compact('products'));
}


    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('products_create', compact('products','categories'));
    }

    public function store(Request $request)
    {
        //validation
        $request->validate([
            'product_name'=>['required','regex:/^[a-zA-Z0-9 ]*$/','max:50'],
            'product_price'=>'required',
            'category_id'=>'required',
            'quantity'=>'required',
            'description'=>'required',
            'product_image'=>'required|mimes:jpg,png,jpeg|max:5048',
        ]);
        
        $imageName = time().'-'.$request->product_name.'.'. $request->product_image->extension();
        $request->product_image->move(public_path('product_image'), $imageName);
        // dd($test);

        $product = Product::create([
            'user_id' => $request->input('user_id'),
            'product_name' => $request->input('product_name'),
            'product_price' => $request->input('product_price'),
            'category_id' => $request->input('category_id'),
            'quantity'=> $request->input('quantity'),
            'description' => $request->input('description'),
            'product_image'=> $imageName
        ]);

        return redirect()->route('vendor.home')->with('message','You have successfully added a product!');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products_edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        //validation
        $validate = $request->validate([
            'product_name'=>['required','regex:/^[a-zA-Z]+$/u','max:50'],
            'product_price'=>'required',
            'category_id'=>'required',
            'quantity'=>'required',
            'description'=>'required',
            'product_image'=>'sometimes|required|mimes:jpg,png,jpeg|max:5048',
        ]);
        
        if($request->hasFile('product_image')){
            $imageName = time().'-'.$request->product_name.'.'. $request->product_image->extension();
            $request->product_image->move(public_path('product_image'), $imageName);
            $product->product_image = $imageName;
        }

        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('product_price');
        $product->category_id = $request->input('category_id');
        $product->quantity = $request->input('quantity');
        $product->description = $request->input('description');
        $product->save();

        return redirect()->route('vendor.home')->with('message', 'You have successfully edited the product!');
    }
    
    public function destroy(Product $product)
    {        
        // Check if the user is authenticated
        if (auth()->Check()) {
        // Get the authenticated user
        $user = Auth()->user();
        
        // Check if the password matches
        if (\Hash::check(request('password'), $user->password)) {
            // Delete the product
            $product->delete();
            return redirect()->route('vendor.home')->with('message', 'Product deleted successfully!');
        }
    }
    
    // If the password doesn't match, redirect back with an error message
    return redirect()->back()->with('error', 'Incorrect password. Failed to delete the product.');
    }
}
