<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'customer_id' => uniqid(),
            'last_four' => substr(strval(rand(1000, 9999)), -4),
            'brand' => $this->getRandomBrand(),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 2,
            'customer_id' => uniqid(),
            'last_four' => substr(strval(rand(1000, 9999)), -4),
            'brand' => $this->getRandomBrand(),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 3,
            'customer_id' => uniqid(),
            'last_four' => substr(strval(rand(1000, 9999)), -4),
            'brand' => $this->getRandomBrand(),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 4,
            'customer_id' => uniqid(),
            'last_four' => substr(strval(rand(1000, 9999)), -4),
            'brand' => $this->getRandomBrand(),
        ];
        DB::table('credit_cards')->insert($param);
        $param = [
            'user_id' => 5,
            'customer_id' => uniqid(),
            'last_four' => substr(strval(rand(1000, 9999)), -4),
            'brand' => $this->getRandomBrand(),
        ];
        DB::table('credit_cards')->insert($param);
    }
}
