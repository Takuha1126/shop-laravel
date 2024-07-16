<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:15',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
            'profile_image' => 'max:7168|mimes:jpg',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.string' => 'ユーザー名には文字列を入力してください。',
            'name.max' => 'ユーザー名は15文字以下で入力してください。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.string' => '郵便番号には文字列を入力してください。',
            'postal_code.max' => '郵便番号は10文字以下で入力してください。',
            'address.required' => '住所は必須です。',
            'address.string' => '住所には文字列を入力してください。',
            'address.max' => '住所は255文字以下で入力してください。',
            'building_name.string' => '建物名には文字列を入力してください。',
            'building_name.max' => '建物名は255文字以下で入力してください。',
            'profile_image.max' => '画像ファイルのサイズは7MB以下にしてください。',
            'profile_image.mimes' => '画像ファイルはJPG形式にしてください。',
        ];
    }
}
