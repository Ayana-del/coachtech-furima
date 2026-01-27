<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentRequest extends FormRequest
{
    /**
     * 1. ログインユーザーのみがコメントを送信することができる
     *
     * @return bool
     */
    public function authorize()
    {
        // ログインしていれば true, していなければ false
        return Auth::check();
    }

    /**
     * 2. 商品コメント：入力必須、最大文字数255
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * エラーメッセージの定義（必要に応じて）
     *
     * @return array
     */
    public function messages()
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは255文字以内で入力してください',
        ];
    }
}