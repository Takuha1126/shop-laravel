<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendAllRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認可ロジックを追加する場合は適宜変更
    }

    public function rules()
    {
        return [
            'message_all' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'message_all.required' => 'メッセージ内容は必須です。',
            'message_all.string' => 'メッセージ内容は文字列である必要があります。',
        ];
    }
}
