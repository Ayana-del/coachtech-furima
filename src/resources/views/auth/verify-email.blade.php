@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <div class="verify-content">
        <p class="verify-text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <div class="verify-action">
            <a href="http://localhost:8025" target="_blank" class="btn-verify">
                認証はこちらから
            </a>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" id="resend-form">
            @csrf
            <a href="javascript:void(0)" onclick="document.getElementById('resend-form').submit();" class="link-resend">
                認証メールを再送する
            </a>
        </form>

        @if (session('status') == 'verification-link-sent')
        <p class="status-message">新しい認証リンクを送信しました。</p>
        @endif
    </div>
</div>
@endsection