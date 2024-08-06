<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'user_id' => 1,
            'product_name' => 'Laptop X1',
            'brand' => 'Brand X',
            'description' => 'Powerful laptop with latest features.',
            'price' => 120000,
            'image' => 'laptop_x1.jpg',
            'status' => 'active',
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 1,
            'product_name' => 'Laptop X1',
            'brand' => 'Brand X',
            'description' => 'Powerful laptop with latest features.',
            'price' => 120000,
            'image' => 'laptop_x1.jpg',
            'status' => 'active',
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 1,
            'product_name' => 'Headphones H3',
            'brand' => 'Brand H',
            'description' => 'Noise-canceling headphones for immersive experience.',
            'price' => 15000,
            'image' => 'headphones_h3.jpg',
            'status' => 'active',
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 3,
            'product_name' => 'Camera C1',
            'brand' => 'Brand C',
            'description' => 'Professional camera for stunning photography.',
            'price' => 200000,
            'image' => 'camera_c1.jpg',
            'status' => 'active',
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 2,
            'product_name' => 'Gaming Console G1',
            'brand' => 'Brand G',
            'description' => 'Next-gen gaming console for ultimate gaming experience.',
            'price' => 50000,
            'image' => 'console_g1.jpg',
            'status' => 'active',
        ];
        DB::table('products')->insert($param);
    }
}

