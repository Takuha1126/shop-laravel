<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageContent; // メールの本文を格納するプロパティ

    /**
     * Create a new message instance.
     *
     * @param string $messageContent
     * @return void
     */
    public function __construct($messageContent)
    {
        $this->messageContent = $messageContent; // コンストラクタでメールの本文を受け取る
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('メールの件名')
                    ->view('admin.emails.send'); // メールのビューを指定
    }
}
