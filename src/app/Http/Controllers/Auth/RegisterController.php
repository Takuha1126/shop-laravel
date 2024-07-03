<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Models\Profile;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'name' => 'デフォルトユーザー名',
            'postal_code' => '000-0000',
            'address' => 'デフォルト住所',
            'building_name' => 'デフォルト建物名',
            'profile_image' => 'default.jpg',
        ]);

        Auth::login($user);

        return redirect()->route('home.index')->with('success', '登録が完了しました。');
    }
}