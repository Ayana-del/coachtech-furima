<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'image_url' => 'nullable|image|mimes:jpeg,png',
            'name' => 'required|string|max:20',
            'postcode' => ['required', 'string', 'regex:/^[0-9]{3}-[0-9]{4}$/'],
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください。',
            'name.max' => 'お名前は20文字以内で入力してください。',
            'postcode.required' => '郵便番号を入力してください。',
            'postcode.regex' => '郵便番号はハイフンありの8文字（例: 123-4567）で入力してください。',
            'address.required' => '住所を入力してください。',
            'image_url.image' => '指定されたファイルが画像ではありません。',
            'image_url.mimes' => 'プロフィール画像は .jpeg または .png 形式でアップロードしてください。',
        ];
    }
}
