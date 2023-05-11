<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\CategoryVendor;

class CategoryVendorTableSeeder extends Seeder
{
    public function run()
    {
        // Get the logged-in vendor's user ID
        $userId = Auth::id();

        // Get all categories
        $categories = Category::all();

        // Loop through each category and create a mapping with the logged-in vendor
        foreach ($categories as $category) {
            $categoryVendor = new CategoryVendor;
            // $categoryVendor->user_id = $userId;
            $categoryVendor->category_id = $category->id;
            $categoryVendor->deleted = false;
            $categoryVendor->save();
        }
    }
}
