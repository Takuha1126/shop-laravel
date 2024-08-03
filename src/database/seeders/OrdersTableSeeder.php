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
            'amount' => '1000',
            'payment_method' => 'credit_card',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 2,
            'product_id' => 2,
            'status' => 'processing',
            'amount' => '2000',
            'payment_method' => 'convenience_store',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 3,
            'product_id' => 3,
            'status' => 'completed',
            'amount' => '1500',
            'payment_method' => 'bank_transfer',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 4,
            'product_id' => 1,
            'status' => 'cancelled',
            'amount' => '10000',
            'payment_method' => 'credit_card',
        ];
        DB::table('orders')->insert($param);
        $param = [
            'user_id' => 2,
            'product_id' => 3,
            'status' => 'pending',
            'amount' => '45000',
            'payment_method' => 'convenience_store',
        ];
        DB::table('orders')->insert($param);
    }
}

