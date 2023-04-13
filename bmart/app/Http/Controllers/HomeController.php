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
        return view('vendorHome');
    }
}
