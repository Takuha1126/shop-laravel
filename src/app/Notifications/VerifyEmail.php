<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyEmail extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verifyUrl = url('/email/verify/' . $this->user->id . '/' . sha1($this->user->email));

        return (new MailMessage)
            ->subject('メールアドレスの確認')
            ->line('このメールは、アカウントのメールアドレス確認のために送信されています。')
            ->action('メールアドレスを確認する', $verifyUrl);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}