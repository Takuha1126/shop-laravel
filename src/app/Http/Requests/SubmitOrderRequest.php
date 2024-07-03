<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法は必ず選択してください。',
        ];
    }
}
