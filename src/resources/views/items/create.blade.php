@extends('layouts.common')

@section('title', '商品の出品')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/create.css') }}">
@endsection

@section('content')
<div class="item-create">
    <div class="item-create__content">
        <div class="item-create__heading">
            <h2>商品の出品</h2>
        </div>

        <form class="item-create__form" action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- 商品画像 --}}
            <div class="item-create__group">
                <div class="item-create__group-title">
                    <p>商品画像</p>
                </div>
                <div class="item-create__image-wrapper">
                    <div class="item-create__image-preview">
                        <img id="preview" src="" alt="">
                    </div>
                    <label class="item-create__image-label">
                        画像を選択する
                        <input type="file" name="img_url" id="img_url" accept="image/*" onchange="previewImage(this)">
                    </label>
                </div>
                <div class="item-create__error">
                    @error('img_url') <p>{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="item-create__section">
                <h3 class="item-create__section-title">商品の詳細</h3>

                {{-- カテゴリー：元の横並びボタン形式 --}}
                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>カテゴリー</p>
                    </div>
                    <div class="item-create__category-group">
                        @foreach($categories as $category)
                        <div class="item-create__category-item">
                            <input type="checkbox" name="categories[]" id="cat-{{ $category->id }}" value="{{ $category->id }}" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <label for="cat-{{ $category->id }}">{{ $category->content }}</label>
                        </div>
                        @endforeach
                    </div>
                    <div class="item-create__error">
                        @error('categories') <p>{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>商品の状態</p>
                    </div>
                    <div class="item-create__select">
                        <select name="condition_id">
                            <option value="" selected disabled>選択してください</option>
                            @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                                {{ $condition->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="item-create__error">
                        @error('condition_id') <p>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="item-create__section">
                <h3 class="item-create__section-title">商品名と説明</h3>

                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>商品名</p>
                    </div>
                    <div class="item-create__input">
                        <input type="text" name="name" value="{{ old('name') }}">
                    </div>
                    <div class="item-create__error">
                        @error('name') <p>{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>ブランド名</p>
                    </div>
                    <div class="item-create__input">
                        <input type="text" name="brand" value="{{ old('brand') }}">
                    </div>
                    <div class="item-create__error">
                        @error('brand') <p>{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>商品の説明</p>
                    </div>
                    <div class="item-create__textarea">
                        <textarea name="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="item-create__error">
                        @error('description') <p>{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="item-create__group">
                    <div class="item-create__group-title">
                        <p>販売価格</p>
                    </div>
                    <div class="item-create__input-price">
                        <span class="price-unit">¥</span>
                        {{-- type="text" にして矢印と数字キーボードを回避 --}}
                        <input type="text" name="price" value="{{ old('price') }}">
                    </div>
                    <div class="item-create__error">
                        @error('price') <p>{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="item-create__button">
                <button class="item-create__button-submit" type="submit">出品する</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection