<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'category_id' => 1,
            'product_id' => 1,
        ];
        DB::table('category_product')->insert($param);
        $param = [
            'category_id' => 2,
            'product_id' => 1,
        ];
        DB::table('category_product')->insert($param);
        $param = [
            'category_id' => 2,
            'product_id' => 3,
        ];
        DB::table('category_product')->insert($param);
        $param = [
            'category_id' => 4,
            'product_id' => 2,
        ];
        DB::table('category_product')->insert($param);
        $param = [
            'category_id' => 3,
            'product_id' => 1,
        ];
        DB::table('category_product')->insert($param);
    }
}
