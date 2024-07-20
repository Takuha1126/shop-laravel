<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Category;
use App\Models\Order;

class ItemController extends Controller
{
    public function index(Request $request) {
        if (Auth::check()) {
            $user = Auth::user();
            $recommendedProducts = Product::where('user_id', '!=', $user->id)
                                            ->whereDoesntHave('orders')
                                            ->inRandomOrder()
                                            ->take(10)
                                            ->get();
            $favoriteProducts = $user->favoriteProducts()->get();
            $categories = Category::all();

            return view('home.index', compact('recommendedProducts', 'favoriteProducts', 'categories'));
        } else {
            $products = Product::inRandomOrder()->take(10)->get();
            return view('home.before', compact('products'));
        }
    }


    public function detail($id) {

        $product = Product::with('categories')->findOrFail($id);
        $categories = Category::all();

        if (Auth::check()) {
            $currentUser = Auth::user();
            $order = Order::where('user_id', $currentUser->id)->latest()->first();
            $isFavorite = Favorite::where('user_id', $currentUser->id)->where('product_id', $id)->exists();
            $soldOut = $product->status === 'purchased';

            return view('detail.after', compact('product', 'order', 'isFavorite', 'categories', 'soldOut'));
        } else {
            return view('detail.before', compact('product', 'categories'));
        }
    }


    public function search(Request $request) {
        $categoryName = $request->input('category_name');

        $productsQuery = Product::whereHas('categories', function ($query) use ($categoryName) {
            $query->where('name', 'like', "%{$categoryName}%");
        });

        if (Auth::check()) {
            $user = Auth::user();
            $productsQuery = $productsQuery->where('user_id', '!=', $user->id);
        }

        $productsQuery->whereDoesntHave('orders');

        $products = $productsQuery->paginate(10);

        $categories = Category::all();
        $recommendedProducts = Product::inRandomOrder()->take(10)->get();

        if (Auth::check()) {
            $user = Auth::user();
            $favoriteProducts = $user->favorites()->get();
            $sessionKey = 'user_' . $user->id . '_searched_products';

            session([$sessionKey => [
                'products' => $products,
                'favoriteProducts' => $favoriteProducts,
            ]]);
            } else {
                $favoriteProducts = collect();
            }

        return view('search', compact('products', 'recommendedProducts', 'favoriteProducts', 'categories'));
    }

}
