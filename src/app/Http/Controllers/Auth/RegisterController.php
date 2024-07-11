<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Notifications\VerifyEmail;

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

        $user->notify(new VerifyEmail());


        Auth::login($user);

        return redirect()->route('auth.verify');
    }

    public function showVerifyForm()
    {
        return view('auth.verify');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = $request->user();
        $user->notify(new VerifyEmail());


        return redirect()->route('auth.verify')->with('resent', true);
    }
}
