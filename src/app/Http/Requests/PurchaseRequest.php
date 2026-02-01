<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // ID11: 支払い方法選択必須
            'payment_method' => ['required'],

            // ID12関連: 配送先（住所）が登録されているか
            'address_check' => [
                function ($attribute, $value, $fail) {
                    $profile = Auth::user()->profile;
                    if (!$profile || empty($profile->address)) {
                        $fail('配送先を登録してください。');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
        ];
    }

    // バリデーション前に仮の値を合成して住所チェックを走らせる
    protected function prepareForValidation()
    {
        $this->merge([
            'address_check' => 'checking',
        ]);
    }
}
