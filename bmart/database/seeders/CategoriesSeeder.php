<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categories')->insert([
            ['category_name'=>'Fruits'],['category_name'=>'Vegetables'],['category_name'=>'Salads & Herbs'],['category_name'=>'Bread'],['category_name'=>'Other Pastries'],['category_name'=>'Tins & Cans'],
            ['category_name'=>'Frozen Seafood'],['category_name'=>'Raw Meats'],['category_name'=>'Wine & Alchohol'],['category_name'=>'Tea & Coffee'],['category_name'=>'Soft Drinks'],['category_name'=>'Dairy Products'],
            ['category_name'=>'Ready Meals']
        ]);
    }
}
