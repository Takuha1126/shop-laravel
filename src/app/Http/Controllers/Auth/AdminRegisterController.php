<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\AdminRegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AdminVerifyEmail;

class AdminRegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    public function register(AdminRegisterRequest $request)
    {
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $admin->notify(new AdminVerifyEmail($admin));

        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.verify');
    }

    public function showVerifyForm()
    {
        return view('admin.verify');
    }

    public function resendVerificationEmail()
    {
        $admin = Auth::guard('admin')->user();
        $admin->notify(new AdminVerifyEmail($admin));

        return redirect()->route('admin.verify')->with('resent', true);
    }

    public function verifyEmail($id, $token)
    {
        $admin = Admin::find($id);

        if ($admin && sha1($admin->email) === $token) {
            $admin->email_verified_at = now();
            $admin->save();

            Auth::guard('admin')->login($admin);

            return redirect()->route('admin.index')->with('verified', true);
        }

        return redirect()->route('admin.index')->with('verification_failed', true);
    }
}
