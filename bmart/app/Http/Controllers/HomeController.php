<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $prods = DB::table('products')
                    ->join('categories','products.category_id', '=', 'categories.id')
                    ->join('users','products.user_id', '=', 'users.id')
                    ->select('products.*','categories.*','users.*','products.id AS prod_id')
                    ->get();
        return view('home', compact('prods'));
    }
    public function vendorHome(){
        // $vendorids = DB::table('users')
        //             ->where('isVendor','=',1)
        //             ->get();
        return view('vendorHome');
        // , compact('vendorids')
    }
}
