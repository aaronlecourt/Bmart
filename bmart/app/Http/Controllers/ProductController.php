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
        // $product_name = $request->product_name;
        // $product_price = $request->product_price;
        // $category_id = $request->category_id;
        // $quantity = $request->quantity;
        // $description = $request->description;
        // $product_image = $request->file('file');
        // $imageName = time().'.'.$product_image->extension();
        // $product_image->move(public_path('images'), $imageName);

        // $product = new Product();
        // $product->product_name = $product_name;
        // $product->product_price = $product_price;
        // $product->category_id = $category_id;
        // $product->quantity = $quantity;
        // $product->description = $description;
        // $product->product_image = $imageName;
        // $product->save();

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
            'product_image'=>'required|mimes:jpg,png,jpeg|max:5048',
        ]);
        
        if($request->hasFile('product_image')){
            $imageName = time().'-'.$request->product_name.'.'. $request->product_image->extension();
            $request->product_image->move(public_path('product_image'), $imageName);
            $product->product_image = $imageName;
        }

        $product = Product::where('id', $product->id)->update([
            'product_name' => $request->input('product_name'),
            'product_price' => $request->input('product_price'),
            'category_id' => $request->input('category_id'),
            'quantity'=> $request->input('quantity'),
            'description' => $request->input('description'),
            'product_image'=> $imageName
        ]);

        // dd($product);
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
