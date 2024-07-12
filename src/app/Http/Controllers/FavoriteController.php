<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function getFavorite(Request $request) {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $favorites = Favorite::where('user_id', $userId)
                            ->with('product')
                            ->get()
                            ->map(function ($favorite) {
                                return [
                                    'id' => $favorite->product->id,
                                    'productName' => $favorite->product->name,
                                    'image' => $favorite->product->image,
                                ];
                            });

        return response()->json($favorites);
    }

    public function toggleFavorite(Request $request) {
        $userId = Auth::id();
        $productId = $request->input('product_id');

        if (!$userId || !$productId) {
            return response()->json(['error' => 'Invalid data'], 400);
        }

        try {
            $isFavorite = $this->checkFavoriteExists($userId, $productId);

            if ($isFavorite) {
                Favorite::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->delete();
            } else {
                Favorite::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);
            }

            $favoriteCount = Favorite::where('product_id', $productId)->count();
            return response()->json(['favoriteCount' => $favoriteCount, 'isFavorite' => !$isFavorite]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to toggle favorite'], 500);
        }
    }

    private function checkFavoriteExists($userId, $productId) {
        return Favorite::where('user_id', $userId)
                       ->where('product_id', $productId)
                       ->exists();
    }
}


