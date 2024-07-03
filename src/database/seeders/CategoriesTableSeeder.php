<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => '家電'],
            ['name' => '家具'],
            ['name' => '衣類'],
            ['name' => '食品'],
            ['name' => '書籍'],
            ['name' => '洋服'],
            ['name' => '男性'],
            ['name' => '女性'],
            ['name' => '子供'],
            ['name' => 'おもちゃ'],
            ['name' => 'その他'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
