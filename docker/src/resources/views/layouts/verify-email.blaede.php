@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="auth__content">
    <div class="auth__heading">
        <h2>メール認証が必要です</h2>
    </div>

    <div class="auth__message">
        <p>登録いただいたメールアドレスに確認用メールを送信しました。</p>
        <p>メール内のリンクをクリックして、会員登録を完了させてください。</p>
    </div>

    <div class="auth__message--sub">
        <p>もしメールが届かない場合は、下のボタンから再送してください。</p>
    </div>

    <form class="form" method="POST" action="{{ route('verification.send') }}">
        @csrf
        <div class="form__button">
            <button class="form__button-submit" type="submit">確認メールを再送する</button>
        </div>
    </form>
</div>
@endsection