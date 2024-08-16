<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class AdminVerifyEmail extends Notification
{
    use Queueable;

    protected $admin;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verifyUrl = url('/admin/verify-email/' . $this->admin->id . '/' . sha1($this->admin->email));

        return (new MailMessage)
            ->subject('メールアドレスの確認')
            ->line('このメールは、アカウントのメールアドレス確認のために送信されています。')
            ->action('メールアドレスを確認する', $verifyUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
