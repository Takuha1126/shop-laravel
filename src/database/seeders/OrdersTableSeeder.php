<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
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
            'product_id' => 1,
            'status' => 'pending',
            'payment_method' => 'credit_card',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 2,
            'product_id' => 2,
            'status' => 'processing',
            'payment_method' => 'convenience_store',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 3,
            'product_id' => 3,
            'status' => 'completed',
            'payment_method' => 'bank_transfer',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 4,
            'product_id' => 1,
            'status' => 'cancelled',
            'payment_method' => 'credit_card',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 2,
            'product_id' => 3,
            'status' => 'pending',
            'payment_method' => 'convenience_store',
        ];
        DB::table('orders')->insert($param);
    }
}

