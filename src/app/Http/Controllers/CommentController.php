<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use App\Models\Category;

class CommentController extends Controller
{
    public function index($id) {
        $product = Product::findOrFail($id);
        $isFavorite = false;
        $categories = Category::all();

        $comments = $product->comments()
                        ->with('user.profile')
                        ->orderBy('created_at', 'desc')
                        ->paginate(3);

        return view('comment', compact('product', 'comments', 'isFavorite', 'categories'));
    }

    public function store(Request $request, $id) {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        if (!$user->profile) {
            return redirect()->back()->with('error', 'プロフィールが見つかりませんでした。');
        }


        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->product_id = $product->id;
        $comment->content = $request->content;
        $comment->profile_id = $user->profile->id;
        $comment->save();

        return redirect()->route('comment.index', ['id' => $id])->with('success', 'コメントを投稿しました。');
    }

    public function destroy($id) {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'コメントを削除する権限がありません。'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }


}

