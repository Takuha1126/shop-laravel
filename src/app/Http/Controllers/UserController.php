<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;
use App\Models\Profile;
use App\Models\Category;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateAddressRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;


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
            if (App::environment('local')) {
                $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            } else {
                $fileName = time() . '_' . $request->file('profile_image')->getClientOriginalName();
                $imagePath = 'profile_images/' . $fileName;
                Storage::disk('s3')->put($imagePath, file_get_contents($request->file('profile_image')));
            }

            $profile->profile_image = $imagePath;
        }

        $profile->save();

        return redirect()->route('user.show');
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

        return redirect()->route('order.details');
    }
}

