<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function getFavorite(Request $request)
    {
        try {
            $userId = Auth::id();
            if (!$userId) {
                return response()->json(['error' => '認証されていません'], 401);
            }

            $productId = $request->query('product_id');
            if (!$productId) {
                return response()->json(['error' => '商品IDが指定されていません'], 400);
            }

            $isFavorite = Favorite::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->exists();

            return response()->json(['isFavorite' => $isFavorite]);
        } catch (\Exception $e) {
            return response()->json(['error' => '内部サーバーエラー'], 500);
        }
    }

    public function toggleFavorite(Request $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');

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
    }

    private function checkFavoriteExists($userId, $productId)
    {
        return Favorite::where('user_id', $userId)
                        ->where('product_id', $productId)
                        ->exists();
    }


}


