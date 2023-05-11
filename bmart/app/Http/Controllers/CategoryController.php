<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use App\Models\CategoryVendor;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    public function index()
    {
        // Get the logged-in vendor's user ID
        $userId = Auth::id();
    
        // Get all associated categories of the logged-in vendor that are not deleted
        // $categories = Category::leftJoin('category_vendor as cv1', function($join) use ($userId) {
        //     $join->on('categories.id', '=', 'cv1.category_id')
        //          ->where('cv1.deleted', false)
        //          ->where(function ($query) use ($userId) {
        //             $query->where('cv1.user_id', $userId)
        //                   ->orWhereNull('cv1.user_id');
        //          })
        //          ->whereNotExists(function ($query) use ($userId) {
        //             $query->select(DB::raw(1))
        //                   ->from('category_vendor as cv2')
        //                   ->whereRaw('cv1.category_id = cv2.category_id')
        //                   ->where('cv2.deleted', false)
        //                   ->whereNotNull('cv2.user_id')
        //                   ->where('cv2.user_id', '<>', $userId);
        //          });
        // })
        // ->select('categories.*')
        // ->get();
        // Get the deleted categories associated with the user
        $remainingCategories = Category::leftJoin('category_vendor', function($join) use ($userId) {
            $join->on('categories.id', '=', 'category_vendor.category_id')
                ->where('category_vendor.user_id', $userId)
                ->where('category_vendor.deleted', true);
        })
        ->whereNotNull('category_vendor.id')
        ->select('categories.id', 'categories.*', 'category_vendor.category_id')
        ->get();

    
        // dd($remainingCategories);
        // Get the non-deleted categories associated with the user
        // $categories = Category::whereHas('vendors', function($query) use ($userId) {
        //     $query->where('user_id', $userId)
        //           ->where('deleted', 0);
        // })->get();
                
        $categories = Category::whereDoesntHave('vendors', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->orWhereHas('vendors', function($query) use ($userId) {
            $query->where('user_id', $userId)
                ->where('deleted', 0);
        })
        ->get();
    
        

        // Get all categories that are not associated with the logged-in vendor
        // $remainingCategories = CategoryVendor::join('categories', 'categories.id', '=', 'category_vendor.category_id')
        // ->where('category_vendor.user_id', $userId)
        // ->where('category_vendor.deleted', true)
        // ->select('categories.id as category_id', 'categories.category_name')
        // ->get();

    
        return view('vendorCategories', compact('categories', 'remainingCategories'));
    }
    

    public function store(Request $request)
    {
        // Get the logged-in vendor's user ID
        $userId = Auth::id();
        
        $categoryVendor = CategoryVendor::where('user_id', $userId)
            ->where('category_id', $request->category_id)
            ->first();
    
        // If the vendor has a mapping, update the deleted status of the category mapping
        if ($categoryVendor) {
            $categoryVendor->deleted = 0;
            $categoryVendor->save();
        }
        // If the vendor does not have a mapping, create a new one
        else {
            $categoryVendor = new CategoryVendor;
            $categoryVendor->user_id = $userId; 
            $categoryVendor->category_id = $request->category_id;
            $categoryVendor->deleted = 0;
            $categoryVendor->save();
        }
    
        return redirect()->back()->with('message', 'Category added successfully.');
    }
    
    public function destroy(Category $category)
    {
        // Get the logged-in user's ID
        $userId = Auth::id();
    
        // Check if the category is associated with the user
        $categoryVendor = CategoryVendor::where('user_id', $userId)
            ->where('category_id', $category->id)
            ->first();
    
        if ($categoryVendor) {
            // If the category is associated with the user, update the "deleted" column
            $categoryVendor->deleted = 1;
            $categoryVendor->save();
        } else {
            // If the category is not associated with the user, create a new mapping with the "deleted" column set to 1
            CategoryVendor::create([
                'user_id' => $userId,
                'category_id' => $category->id,
                'deleted' => 1
            ]);
        }
    
        // Delete all associated products with the category
        Product::where('user_id', $userId)
            ->where('category_id', $category->id)
            ->delete();
    
        // Redirect back to the index page with a success message
        return redirect()->route('categories.index')->with('success', 'Category has been deleted.');
    }
    
    
}