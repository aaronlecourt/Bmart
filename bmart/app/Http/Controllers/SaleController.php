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
use Dompdf\Dompdf;
use Dompdf\Options;


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
        
         // Store the filter values in the session
        // $request->session()->put('categoryFilter', $categoryFilter);
        // $request->session()->put('statusFilter', $statusFilter);
        // $request->session()->put('monthFilter', $monthFilter);
        // $request->session()->put('dayFilter', $dayFilter);
        // $request->session()->put('yearFilter', $yearFilter);
        // $request->session()->put('sortBy', $sortBy);

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
                // $totalSales += $product->total_earnings; // Add current earnings to total sales
            } else {
                $product->total_earnings = '-'; // Set total earnings to 0 for cancelled or pending products
            }
        }
        
        // Retrieve the product order counts for the logged-in vendor
        $results = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select('products.product_name', DB::raw('COUNT(*) as order_count'))
        ->where('products.user_id', $vendor->id)
        ->groupBy('order_items.product_id','products.product_name')
        ->get();

        // Prepare the data for charting
        $labels = [];
        $data = [];
        $colors = ['#dc3545','#007bff','#28a745','#ffc107','#17a2b8']; // Bootstrap colors
        // $colorIndex = 0;

        foreach ($results as $result) {
        $labels[] = $result->product_name;
        $data[] = $result->order_count;
        }

        $totalSales = 0; // Initialize total sales variable
        // dd($labels, $data);
        if ($request->has('generate')) {
            
            // dd($categoryFilter, $statusFilter, $dayFilter, $monthFilter, $yearFilter, $sortBy);
            // Apply the filters to the query
            $productsQuery = $productsQuery->whereNotNull('products.category_id');
            
            // dd($productsQuery);
            if (!empty($categoryFilter)) {
                $productsQuery = $productsQuery->where('categories.category_name', $categoryFilter);
            }
            if (!empty($statusFilter)) {
                $productsQuery = $productsQuery->where('order_items.status', $statusFilter);
            }
            if (!empty($dayFilter)) {
                $productsQuery = $productsQuery->whereDay('order_items.created_at', $dayFilter);
            }
            if (!empty($monthFilter)) {
                $productsQuery = $productsQuery->whereMonth('order_items.created_at', $monthFilter);
            }
            if (!empty($yearFilter)) {
                $productsQuery = $productsQuery->whereYear('order_items.created_at', $yearFilter);
            }
        
            if ($sortBy === 'product_sort') {
                $productsQuery->orderBy('products.product_name');
            } elseif ($sortBy === 'date_sort') {
                $productsQuery->orderBy('order_items.created_at');
            }
        
            // Get the filtered data
            $filteredData = $productsQuery->get();

            // Calculate total earnings for each product group
            foreach ($filteredData as $product) {
                if ($product->status !== 'cancelled' && $product->status !== 'pending') {
                    $totalEarnings = OrderItem::where('product_id', $product->product_id)
                        ->where('status', $product->status)
                        ->whereDay('created_at', $product->day)
                        ->whereMonth('created_at', $product->month)
                        ->whereYear('created_at', $product->year)
                        ->sum(DB::raw('product_price * quantity'));

                    $product->total_earnings = $totalEarnings;
                    $totalSales = $filteredData->sum(function ($product) {
                        return is_numeric($product->total_earnings) ? $product->total_earnings : 0;
                    });
                } else {
                    $product->total_earnings = '-'; // Set total earnings to '-' for cancelled or pending products
                }
            }

            // dd($productsQuery);
            // Render the filtered data into an HTML table with border styling
            $html = '<h2 style="text-align:center; font-family: Nunito, sans-serif">BerryMart Product Sales</h2>';
            $html .= '<table class="table table-hover" id="salesTable" style="border-collapse: collapse; width: 100%">';
            $html .= '<thead>';
            $html .= '<tr>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">Product Name</th>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">Product Category</th>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">Status</th>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">No. of items</th>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">Total Earnings</th>';
            $html .= '<th style="text-align:center; font-family: Nunito, sans-serif">Date</th>';    
            $html .= '</tr>';
            $html .= '</thead>';
            $html .= '<tbody style="border:1px solid rgba(0,0,0,0.2)">';
        
            if ($filteredData->isEmpty()) {
                $html .= '<tr>';
                $html .= '<td colspan="6" style="font-weight:600; color:gray; font-family: Nunito, sans-serif">No records found.</td>';
                $html .= '</tr>';
            } else {
                foreach ($filteredData as $product) {
                    $html .= '<tr>';
                    $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px; font-size:10pt; font-family: Nunito, sans-serif">' . $product->product_name . '</td>';
                    $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px; font-size:10pt; font-family: Nunito, sans-serif">' . $product->product_category . '</td>';
                    
        
                    if ($product->status == 'pending') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="bg-warning text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:#ffc107; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'confirmed') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="bg-success text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:#28a745; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'for approval') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="bg-secondary text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:gray; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'request rejected') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span style="padding: 10px;border-color:gray; color:gray; border-radius:15px; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'cancelled') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="bg-danger text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:#dc3545; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'shipped') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:orange; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    } elseif ($product->status == 'delivered') {
                        $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2); font-size:10pt; font-family: Nunito, sans-serif"><span class="bg-primary text-white px-2 rounded-pill" style="padding: 0 10px;;background-color:#007bff; border-radius:15px; color:white; font-weight:bold">' . $product->status . '</span></td>';
                    }

                    $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px; font-size:10pt; font-family: Nunito, sans-serif">' . $product->total_quantity . '</td>';
                    $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px; font-size:10pt; font-family: Nunito, sans-serif">' . $product->total_earnings . '</td>';
                    $html .= '<td style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px; font-size:10pt; font-family: Nunito, sans-serif">' . \Carbon\Carbon::createFromFormat('m', sprintf('%02d', $product->month))->format('F') . ' ' . $product->day . ', ' . $product->year . '</td>';
                    $html .= '</tr>';
                }
        
                $html .= '<tr>';
                $html .= '<td colspan="4" style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px;"><h6 style="font-size:14pt; text-align:right; font-family: Nunito, sans-serif;">Total Sales:</h6></td>';
                $html .= '<td colspan="2" style="border-bottom:1px solid rgba(0,0,0,0.2);padding: 10px;"><h6 style="font-size:14pt; text-align:left; font-family: Nunito, sans-serif;">'. 'P'.number_format($totalSales, 2) . '</h6></td>';
                $html .= '</tr>';
            }
        
            // Create a new instance of Dompdf
            $dompdf = new Dompdf();

            // Load the HTML content into Dompdf
            // $dompdf->loadHtml('<link rel="stylesheet" type="text/css" href="' . public_path('css/bootstrap.min.css') . '">' . $html);
            // Create a new instance of Dompdf with custom options
            $options = new Options();
            $options->setIsRemoteEnabled(true); // Enable remote file access

            $dompdf = new Dompdf($options);

            // Load the HTML content into Dompdf
            $dompdf->loadHtml('<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">' . $html);
        
            // (Optional) Customize Dompdf settings if needed
            $dompdf->setPaper('A4');
            
            // Render the PDF content
            $dompdf->render();

            // Get the PDF content as a string
            $pdfContent = $dompdf->output();

            // Set the file name for the downloaded file
            $filename = 'BerryMart Product Sales Report.pdf';

            // Set the content disposition header to force the browser to download the file
            $headers = [
                'Content-Type' => 'application/pdf',
            ];

            // Return the PDF file as a downloadable response
            return response($pdfContent, 200, $headers);
        }
           
        
        else{
            return view('vendorSales', compact('categories', 'products', 'categoryFilter', 'statusFilter', 'monthFilter', 'dayFilter', 'yearFilter', 'sortBy','totalResults','totalSales','labels','data','results', 'colors'));
        }
    }

    public function clearFilters()
    {
        return redirect()->route('vendor.sales');
    }
    
   
}
