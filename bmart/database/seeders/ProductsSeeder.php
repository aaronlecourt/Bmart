<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id'=>'2',
                'user_id'=>'1',
                'product_name'=>'Carrot',
                'product_price'=>'50',
                'quantity'=>'100',
                'description'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint quo pariatur quam mollitia eos.',
            ],
            [
                'category_id'=>'1',
                'user_id'=>'1',
                'product_name'=>'Apple',
                'product_price'=>'20',
                'quantity'=>'85',
                'description'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint quo pariatur quam mollitia eos.',
            ],
            [
                'category_id'=>'1',
                'user_id'=>'3',
                'product_name'=>'Grapes',
                'product_price'=>'100',
                'quantity'=>'50',
                'description'=>'Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint quo pariatur quam mollitia eos.',
            ]         
        ]);
    }
}
