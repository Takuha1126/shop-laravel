<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommentsTableSeeder extends Seeder
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
            'profile_id' => 1,
            'product_id' => 1,
            'content' => Str::random(50),
        ];
        DB::table('comments')->insert($param);
        $param = [
            'user_id' => 1,
            'profile_id' => 1,
            'product_id' => 1,
            'content' => Str::random(50),
        ];
        DB::table('comments')->insert($param);
        $param = [
            'user_id' => 2,
            'profile_id' => 2,
            'product_id' => 1,
            'content' => Str::random(50),
        ];
        DB::table('comments')->insert($param);
        $param = [
            'user_id' => 3,
            'profile_id' => 3,
            'product_id' => 1,
            'content' => Str::random(50),
        ];
        DB::table('comments')->insert($param);
        $param = [
            'user_id' => 1,
            'profile_id' => 1,
            'product_id' => 2,
            'content' => Str::random(50),
        ];
        DB::table('comments')->insert($param);
    }
}
