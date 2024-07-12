<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'message_content' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'ユーザーは必須です。',
            'user_id.exists' => '指定されたユーザーIDが存在しません。',
            'message_content.required' => 'メッセージ内容は必須です。',
            'message_content.string' => 'メッセージ内容は文字列である必要があります。',
        ];
    }
}
