<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'コメント内容を入力してください。',
            'content.string' => 'コメント内容は文字列である必要があります。',
            'content.max' => 'コメント内容は255文字以内で入力してください。',
        ];
    }
}
