<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'email' => 'example@example.com',
            'password' => Hash::make('password123'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'email' => 'user1@example.com',
            'password' => Hash::make('password161'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'email' => 'user2@example.com',
            'password' => Hash::make('password456'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'email' => 'user3@example.com',
            'password' => Hash::make('password678'),
        ];
        DB::table('users')->insert($param);
        $param = [
            'email' => 'user4@example.com',
            'password' => Hash::make('password738'),
        ];
        DB::table('users')->insert($param);
    }
}

