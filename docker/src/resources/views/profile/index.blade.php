@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('search')
<div class="header__search-bar">
    <form action="/" method="get">
        <input type="text" name="keyword" placeholder="何をお探しですか？">
    </form>
</div>
@endsection

@section('content')
<div class="profile__content">
    <div class="profile__heading">
        <h2>プロフィール設定</h2>
    </div>

    <form class="form" action="{{ route('profile.store') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf

        {{-- FN029: プロフィール画像設定 --}}
        <div class="form__group">
            <div class="profile-image__flex">
                <div class="profile-image__preview">
                    @if($profile->image_url)
                    {{-- 登録済み画像がある場合 --}}
                    <img src="{{ asset('storage/' . $profile->image_url) }}" alt="ユーザーアイコン" class="user-icon">
                    @else
                    {{-- 画像がない場合は絵文字アイコンを表示 --}}
                    <div class="user-icon default-emoji">
                        👤
                    </div>
                    @endif
                </div>
                <label class="profile-image__label">
                    画像を選択する
                    <input type="file" name="image_url" class="profile-image__input">
                </label>
            </div>
            <div class="form__error">
                @error('image_url')
                {{ $message }}
                @enderror
            </div>
        </div>

        {{-- FN027: ユーザー名 (usersテーブル) --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        {{-- FN027: 郵便番号 (profilesテーブル) --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postcode" value="{{ old('postcode', $profile->postcode) }}">
                </div>
                <div class="form__error">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        {{-- FN027: 住所 (profilesテーブル) --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', $profile->address) }}">
                </div>
                <div class="form__error">
                    @error('address')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        {{-- FN027: 建物名 (profilesテーブル) --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $profile->building) }}">
                </div>
                <div class="form__error">
                    @error('building')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection