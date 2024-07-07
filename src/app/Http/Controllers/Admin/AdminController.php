<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;

class AdminController extends Controller
{
    public function index() {
        $users = User::with('profile')->get();
        return view('admin.index', compact('users'));
    }

    public function comment(User $user) {
        $comments = Comment::where('user_id', $user->id)->with('product')->orderBy('created_at', 'desc')->get();
        return view('admin.comment', compact('user','comments'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->comment()->delete();
        $user->product()->delete();
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'ユーザーが削除されました');
    }

    public function remove($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'コメントを削除しました。');
    }
}
