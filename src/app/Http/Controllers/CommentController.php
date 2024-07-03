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
    public function index($id)
{
    // 商品を取得する
    $product = Product::findOrFail($id);

    // お気に入り状態を初期化する（お気に入り機能がある場合は適宜修正する）
    $isFavorite = false;

    // 全てのカテゴリを取得する（必要に応じてカテゴリがある場合の処理を追加する）
    $categories = Category::all();

    // 商品に紐づくコメントをページネーションして取得する
    $comments = $product->comments()
                        ->with('user.profile') // ユーザーのプロフィール情報も同時に取得する
                        ->orderBy('created_at', 'desc') // 適切な並び順を設定する
                        ->paginate(3); // ページごとに10件のコメントを取得する

    return view('comment', compact('product', 'comments', 'isFavorite', 'categories'));
}

    public function store(Request $request, $id)
{
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

public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'コメントを削除する権限がありません。'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }


}

