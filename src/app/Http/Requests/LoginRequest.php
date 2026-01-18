<?php

namespace App\Http\Requests;

// 【重要】FormRequestではなく、FortifyのLoginRequestをインポートして別名を付ける
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;

// 継承先を FortifyLoginRequest に変更する
class LoginRequest extends FortifyLoginRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // ログイン試行は全員に許可するため true
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
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => 'メールアドレスを入力してください',
            'email.email'       => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください',
        ];
    }
}
