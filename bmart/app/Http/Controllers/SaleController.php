<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::all();
        // Get the logged-in user (vendor)
        $vendor = Auth::user();
        
        // Get filter values from request
        $categoryFilter = $request->input('category_filter');
        $statusFilter = $request->input('status_filter');
        $monthFilter = $request->input('month_filter');
        $dayFilter = $request->input('day_filter');
        $yearFilter = $request->input('year_filter');
        $sortBy = $request->input('sort_by');
        
        // Fetch the products with their respective category, status, and total quantity, including date components
        $productsQuery = Product::select(
            'products.id as product_id',
            'products.product_name',
            'categories.category_name as product_category',
            'order_items.status',
            // 'order_items.created_at',
            DB::raw('SUM(order_items.quantity) as total_quantity'),
            DB::raw('MONTH(order_items.created_at) as month'),
            DB::raw('DAY(order_items.created_at) as day'),
            DB::raw('YEAR(order_items.created_at) as year')
        )
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->where('products.user_id', $vendor->id);
            // ->groupBy('products.id', 'products.product_name', 'categories.category_name', 'order_items.status', 'order_items.created_at');

        
        // Apply filters
        if ($categoryFilter) {
            $productsQuery->where('products.category_id', $categoryFilter);
        }
        if ($statusFilter) {
            $productsQuery->where('order_items.status', $statusFilter);
        }
        if ($monthFilter) {
            $productsQuery->whereMonth('order_items.created_at', $monthFilter);
        }
        if ($dayFilter) {
            $productsQuery->whereDay('order_items.created_at', $dayFilter);
        }
        if ($yearFilter) {
            $productsQuery->whereYear('order_items.created_at', $yearFilter);
        }
        
        // Apply sorting
        if ($sortBy === 'product_sort') {
            $productsQuery->orderBy('products.product_name');
        } elseif ($sortBy === 'date_sort') {
            $productsQuery->orderBy('order_items.created_at');
        }

        // Execute the query and group the results
        $products = $productsQuery->groupBy(
            'products.id',
            'products.product_name',
            'categories.category_name',
            'order_items.status',
            DB::raw('MONTH(order_items.created_at)'),
            DB::raw('DAY(order_items.created_at)'),
            DB::raw('YEAR(order_items.created_at)')
        )->paginate(10);

        $totalResults = $products->total();
        
        // Calculate total earnings for each product group
        foreach ($products as $product) {
            if ($product->status !== 'cancelled' && $product->status !== 'pending') {
                $totalEarnings = OrderItem::where('product_id', $product->product_id)
                    ->where('status', $product->status)
                    ->whereDay('created_at', $product->day)
                    ->whereMonth('created_at', $product->month)
                    ->whereYear('created_at', $product->year)
                    ->sum(DB::raw('product_price * quantity'));

                $product->total_earnings = $totalEarnings;

                $totalSales = 0; // Initialize total sales variable
                // $totalSales += $product->total_earnings; // Add current earnings to total sales
            } else {
                $product->total_earnings = '-'; // Set total earnings to 0 for cancelled or pending products
            }
        }

        return view('vendorSales', compact('categories', 'products', 'categoryFilter', 'statusFilter', 'monthFilter', 'dayFilter', 'yearFilter', 'sortBy','totalResults','totalSales'));
    }

    public function clearFilters()
    {
        return redirect()->route('vendor.sales');
    }
    
   
}
