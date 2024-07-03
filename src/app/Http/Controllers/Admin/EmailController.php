<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SendNotificationRequest;
use App\Http\Requests\SendAllRequest;


class EmailController extends Controller
{
    public function show() {
        $users = User::with('profile')->get();
        return view('admin.email', compact('users'));
    }

    public function sendNotification(SendNotificationRequest $request)
{
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'message_content' => 'required|string',
    ]);


    $user = User::findOrFail($request->user_id);

    $messageContent = $request->message_content;
    Mail::to($user->email)->send(new SendEmail($messageContent));

    $users = User::with('profile')->get();

    return view('admin.email', compact('messageContent','users'))->with('success', 'メールを送信しました。');
}

    public function sendAll(SendAllRequest $request)
{
    $messageContent = $request->message_all;

    $users = User::all();
    foreach ($users as $user) {
        Mail::to($user->email)->send(new SendEmail($messageContent));
    }

    return redirect()->back()->with('success', '全員にお知らせメールを送信しました');
}

}


