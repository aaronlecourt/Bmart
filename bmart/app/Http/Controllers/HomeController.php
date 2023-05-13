<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $userId = \Auth::id();
        $srch = $request->input('search');
        $categs = DB::table('categories')->get();
        $vend = DB::table('users')
            ->where('isVendor','=','1')
            ->get();
            $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('users', 'products.user_id', '=', 'users.id')
            ->select('products.*', 'categories.*', 'users.*', 'users.id AS vendor_id','products.id AS prod_id', 'categories.id AS categ_id');
    

        // Check if search query is present in the request
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', '%'.$search.'%')
                ->orWhere('products.id', 'like', '%'.$search.'%')
                ->orWhere('product_price', 'like', '%'.$search.'%')
                ->orWhere('quantity', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('category_name', 'like', '%'.$search.'%');
                //   ->orWhereRaw("DATE_FORMAT(products.created_at, '%b %d, %Y') LIKE '%$search%'");
            });        
        }

        $prods = $query->paginate(12);
        // Append search parameter to pagination links
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $prods->appends(['search' => $search]);
            // Count the number of search results
            $count = $query->count();
        }
        else{
            $count = 0;
        }

        return view('home', compact('prods','categs','vend', 'srch', 'userId', 'count'));
    }
    public function vendorHome(){
        return view('vendorHome', compact('products'));
    }
}
