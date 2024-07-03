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
        $users = User::all();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'name' => 'デフォルトユーザー名',
                'postal_code' => '000-0000',
                'address' => 'デフォルト住所',
                'building_name' => 'デフォルト建物名',
                'profile_image' => 'default.jpg',
            ]);
        }
    }
}
