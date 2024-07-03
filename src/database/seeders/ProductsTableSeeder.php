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
            'status' => 'active',
            'productName' => 'Laptop X1',
            'brand' => 'Brand X',
            'description' => 'Powerful laptop with latest features.',
            'price' => 120000,
            'image' => 'laptop_x1.jpg',
            'is_recommended' => true,
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 1,
            'status' => 'active',
            'productName' => 'Laptop X1',
            'brand' => 'Brand X',
            'description' => 'Powerful laptop with latest features.',
            'price' => 120000,
            'image' => 'laptop_x1.jpg',
            'is_recommended' => true,
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 1,
            'status' => 'active',
            'productName' => 'Headphones H3',
            'brand' => 'Brand H',
            'description' => 'Noise-canceling headphones for immersive experience.',
            'price' => 15000,
            'image' => 'headphones_h3.jpg',
            'is_recommended' => false,
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 3,
            'status' => 'active',
            'productName' => 'Camera C1',
            'brand' => 'Brand C',
            'description' => 'Professional camera for stunning photography.',
            'price' => 200000,
            'image' => 'camera_c1.jpg',
            'is_recommended' => true,
        ];
        DB::table('products')->insert($param);
        $param = [
            'user_id' => 2,
            'status' => 'active',
            'productName' => 'Gaming Console G1',
            'brand' => 'Brand G',
            'description' => 'Next-gen gaming console for ultimate gaming experience.',
            'price' => 50000,
            'image' => 'console_g1.jpg',
            'is_recommended' => false,
        ];
        DB::table('products')->insert($param);
    }
}

