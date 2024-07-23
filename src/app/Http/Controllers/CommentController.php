<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function index($id) {
        $product = Product::findOrFail($id);
        $categories = Category::all();

        $comments = $product->comments()
                        ->with('user.profile')
                        ->orderBy('created_at', 'desc')
                        ->paginate(3);

        return view('comment', compact('product', 'comments','categories'));
    }

    public function store(CommentRequest $request, $id) {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->product_id = $product->id;
        $comment->content = $request->content;
        $comment->profile_id = $user->profile->id;
        $comment->save();

        return redirect()->route('comment.index', ['id' => $id]);
    }

    public function destroy($id) {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return response()->json(['success' => true]);
    }


}

