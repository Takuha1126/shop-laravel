<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認可のロジックが必要な場合に適宜修正
    }

    public function rules()
    {
        return [
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.max' => '郵便番号は10文字以内で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building_name.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
