<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
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
            'name' => '山田 太郎',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区1-1-1',
            'building_name' => '新宿ビル',
            'profile_image' => 'https://via.placeholder.com/150',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 2,
            'name' => '鈴木 花子',
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市2-2-2',
            'building_name' => '大阪タワー',
            'profile_image' => 'https://via.placeholder.com/150',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 3,
            'name' => '田中 一郎',
            'postal_code' => '456-7890',
            'address' => '神奈川県横浜市3-3-3',
            'building_name' => '横浜ランドマーク',
            'profile_image' => 'https://via.placeholder.com/150',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 4,
            'name' => '佐藤 二郎',
            'postal_code' => '654-3210',
            'address' => '愛知県名古屋市4-4-4',
            'building_name' => '名古屋ドーム',
            'profile_image' => 'https://via.placeholder.com/150',
        ];
        DB::table('profiles')->insert($param);
        $param = [
            'user_id' => 5,
            'name' => '小林 三郎',
            'postal_code' => '321-6549',
            'address' => '北海道札幌市5-5-5',
            'building_name' => '札幌タワー',
            'profile_image' => 'https://via.placeholder.com/150',
        ];
        DB::table('profiles')->insert($param);
    }
}
