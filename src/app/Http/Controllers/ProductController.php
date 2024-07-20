<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $file = $request->file('image');
        $fileSize = $file->getSize();

        if (App::environment('local')) {
            $imagePath = $file->store('images', 'public');
        } else {
            $imagePath = $file->store('images', 's3');
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

    return redirect()->back()->with('success', '商品を出品しました。<br>なお、出品した商品は出品者のホーム画面と検索欄には表示されませんのでご注意ください。');
}

}