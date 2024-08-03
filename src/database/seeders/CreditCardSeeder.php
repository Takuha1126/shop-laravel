<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreditCardSeeder extends Seeder
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
            'customer_id' => Str::random(10),
            'payment_method_id' => Str::random(10),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 2,
            'customer_id' => Str::random(10),
            'payment_method_id' => Str::random(10)
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 3,
            'customer_id' => Str::random(10),
            'payment_method_id' => Str::random(10),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 4,
            'customer_id' => Str::random(10),
            'payment_method_id' => Str::random(10),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 5,
            'customer_id' => Str::random(10),
            'payment_method_id' => Str::random(10),
        ];
        DB::table('credit_cards')->insert($param);
    }
}
