<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Category;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateAddressRequest;

class UserController extends Controller
{
    public function show()
    {
        $userId = Auth::id();
        $profile = Profile::where('user_id', $userId)->first();
        $categories = Category::all();

        $productsForSale = Product::where('user_id', $userId)->get();

        $purchasedProducts = Order::where('user_id', $userId)->get();

        return view('user.mypage', compact('productsForSale', 'purchasedProducts', 'profile', 'categories'));
    }

    public function edit($id)
    {
        $profile = Profile::where('user_id', $id)->firstOrFail();
        $products = Product::all();
        $categories = Category::all();
        return view('user.edit', compact('profile', 'products', 'categories'));
    }

    public function update(ProfileUpdateRequest $request, $id)
    {
        $profile = Profile::where('user_id', $id)->first();

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $id;
        }

        $profile->name = $request->name;
        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building_name = $request->building_name;

        if ($request->hasFile('profile_image')) {
        $storageDisk = env('FILESYSTEM_DRIVER', 'public');
        $imagePath = $request->file('profile_image')->store('profile_images', $storageDisk);
        $profile->profile_image = $imagePath;
    }

        $profile->save();

        return redirect()->route('user.show')->with('success', 'プロフィールが更新されました。');
    }

    public function editAddress()
    {
        $userId = Auth::id();
        $profile = Profile::where('user_id', $userId)->first();
        return view('change', compact('profile'));
    }

    public function updateAddress(UpdateAddressRequest $request)
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        $profile->postal_code = $request->postal_code;
        $profile->address = $request->address;
        $profile->building_name = $request->building_name;

        $profile->save();

        return redirect()->route('order.details')->with('success', '住所が更新されました。');
    }
}

