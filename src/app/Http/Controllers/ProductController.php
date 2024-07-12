<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ProductRequest $request)
{
    $user = Auth::user();

    $product = new Product();
    $product->user_id = $user->id;
    $product->productName = $request->productName;
    $product->brand = $request->brand;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->status = $request->status;

    if ($request->hasFile('image')) {
        if (App::environment('local')) {
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = $request->file('image')->store('images', 's3');
        }
        $product->image = $imagePath;
    }



    $product->save();

    $categoryNames = $request->input('categories', []);
    foreach ($categoryNames as $categoryName) {
        $trimmedCategoryName = trim($categoryName);
        $category = Category::firstOrCreate(['name' => $trimmedCategoryName]);
        $product->categories()->attach($category->id);
    }

    return redirect()->route('home.index');
}

}