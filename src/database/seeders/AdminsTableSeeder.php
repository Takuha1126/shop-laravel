<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
            'name' => 'Admin1',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password1'),
        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => 'Admin2',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password2'),

        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => 'Admin3',
            'email' => 'admin3@example.com',
            'password' => Hash::make('password3'),
        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => 'Admin4',
            'email' => 'admin4@example.com',
            'password' => Hash::make('password4'),
        ];
        DB::table('admins')->insert($param);
        $param = [
            'name' => 'Admin5',
            'email' => 'admin5@example.com',
            'password' => Hash::make('password5'),
        ];
        DB::table('admins')->insert($param);
    }
}

