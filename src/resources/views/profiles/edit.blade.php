@extends('layouts.common')

@section('search')
<form action="{{ route('item.index') }}" method="get" class="header-search-form">
    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/profiles/edit.css') }}">
@endsection

@section('content')
<div class="profile__content">
    <div class="profile__heading">
        <h2>プロフィール設定</h2>
    </div>

    @if (session('message'))
    <div class="success-message">
        {{ session('message') }}
    </div>
    @endif

    <form class="form" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PATCH')

        <div class="form__group">
            <div class="profile-image__flex">
                <div class="profile-image__preview" id="image-preview-container">
                    @if ($profile->image_url)
                    <img src="{{ asset('storage/' . $profile->image_url) }}" alt="ユーザーアイコン" class="user-icon" id="preview-img">
                    @else
                    <div class="default-emoji" id="preview-default">👤</div>
                    <img src="" alt="ユーザーアイコン" class="user-icon" id="preview-img" style="display: none;">
                    @endif
                </div>
                <label class="profile-image__label">
                    画像を選択する
                    <input type="file" name="image_url" class="profile-image__input" onchange="previewImage(this)">
                </label>

                @if ($profile->image_url)
                <div class="profile-image__delete">
                    <input type="checkbox" name="delete_image" id="delete_image" value="1" onchange="toggleDelete(this)">
                    <label for="delete_image">画像を削除する</label>
                </div>
                @endif
            </div>
            <div class="form__error">
                @error('image_url') {{ $message }} @enderror
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title"><span class="form__label--item">ユーザー名</span></div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form__error">
                    @error('name') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title"><span class="form__label--item">郵便番号</span></div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="postcode" value="{{ old('postcode', $profile->postcode) }}">
                </div>
                <div class="form__error">
                    @error('postcode') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title"><span class="form__label--item">住所</span></div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', $profile->address) }}">
                </div>
                <div class="form__error">
                    @error('address') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title"><span class="form__label--item">建物名</span></div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $profile->building) }}">
                </div>
                <div class="form__error">
                    @error('building') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">
                @if (empty($profile->address))
                設定完了
                @else
                更新する
                @endif
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        const previewImg = document.getElementById('preview-img');
        const previewDefault = document.getElementById('preview-default');

        // input.files[0] を直接書かず、一度変数に受けることで解析エラーを回避しやすくします
        const files = input.files;
        if (files && files.length > 0) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                if (previewDefault) {
                    previewDefault.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    function toggleDelete(checkbox) {
        const previewImg = document.getElementById('preview-img');
        const previewDefault = document.getElementById('preview-default');
        const fileInput = document.querySelector('.profile-image__input');

        if (checkbox.checked) {
            previewImg.style.display = 'none';
            if (previewDefault) {
                previewDefault.style.display = 'flex';
            }
            fileInput.value = "";
        }
    }
</script>
@endsection