<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use App\Models\Product;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::with('profile')->paginate(5);
        return view('admin.index', compact('users'));
    }

    public function comment(User $user)
    {
        $comments = Comment::where('user_id', $user->id)
                            ->with('product')
                            ->orderBy('created_at', 'desc')
                            ->paginate(5);
        return view('admin.comment', compact('user', 'comments'));
    }

    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $products = Product::where('user_id', $user->id)->get();

        foreach ($products as $product) {
            if (!$product->orders()->exists()) {
                $product->delete();
            }
        }


        $user->comments()->delete();
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'ユーザーが削除されました');
    }

    public function destroyComment($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'コメントを削除しました。');
    }
}
