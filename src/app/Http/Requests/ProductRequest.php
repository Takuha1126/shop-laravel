<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpg|max:7168',
            'categories' => 'required',
            'status' => 'required|string|max:255',
            'productName' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.image' => '有効な画像ファイルを選択してください。',
            'image.mimes' => '画像ファイルはJPGにしてください。',
            'image.max' => '画像ファイルのサイズは7MB以下にしてください。',
            'categories.required' => 'カテゴリーを入力してください。',
            'status.required' => '商品の状態を入力してください。',
            'status.string' => '商品の状態は文字列で入力してください。',
            'status.max' => '商品の状態は255文字以内で入力してください。',
            'productName.required' => '商品名を入力してください。',
            'productName.string' => '商品名は文字列で入力してください。',
            'productName.max' => '商品名は255文字以内で入力してください。',
            'brand.required' => 'ブランド名を入力してください。',
            'brand.string' => 'ブランド名は文字列で入力してください。',
            'brand.max' => 'ブランド名は255文字以内で入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'description.string' => '商品の説明は文字列で入力してください。',
            'price.required' => '販売価格を入力してください。',
            'price.numeric' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0以上で入力してください。',
        ];
    }
}
