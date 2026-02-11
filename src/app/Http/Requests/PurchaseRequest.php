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
            'payment_method' => ['required'],

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

    protected function prepareForValidation()
    {
        $this->merge([
            'address_check' => 'checking',
        ]);
    }
}
