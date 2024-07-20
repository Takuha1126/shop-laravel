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
            'profile_image' => 'max:7168|mimes:jpg',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名は必須です。',
            'name.string' => 'ユーザー名には文字列を入力してください。',
            'name.max' => 'ユーザー名は15文字以下で入力してください。',
            'profile_image.max' => '画像ファイルのサイズは7MB以下にしてください。',
            'profile_image.mimes' => '画像ファイルはJPG形式にしてください。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'address.required' => '住所は必須です。',
            'address.string' => '住所には文字列を入力してください。',
            'address.max' => '住所は255文字以下で入力してください。',
            'building_name.string' => '建物名には文字列を入力してください。',
            'building_name.max' => '建物名は255文字以下で入力してください。',
        ];
    }
}
