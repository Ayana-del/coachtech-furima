@extends('layouts.common')

@section('search')
<form action="{{ route('item.index') }}" method="get" class="header-search-form">
    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<main class="item-detail">
    {{-- 左側：画像固定エリア --}}
    <div class="item-detail__left">
        <img src="{{ asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}" class="main-image">
    </div>

    {{-- 右側：スクロール情報エリア --}}
    <div class="item-detail__right">
        {{-- 商品タイトル・金額 --}}
        <section class="item-header">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="brand-name">{{ $item->brand }}</p>
            <p class="item-price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

            <div class="stats-row">
                {{-- いいねボタン --}}
                <div class="stat-group">
                    <form action="{{ route('items.like', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="icon-btn">
                            <img src="{{ asset(auth()->check() && $isLiked ? 'img/ハートロゴ_ピンク.png' : 'img/ハートロゴ_デフォルト.png') }}" alt="いいね">
                        </button>
                    </form>
                    <span class="count">{{ $item->likes->count() }}</span>
                </div>
                {{-- コメント数 --}}
                <div class="stat-group">
                    <div class="icon-btn">
                        <img src="{{ asset('img/ふきだしロゴ.png') }}" alt="コメント">
                    </div>
                    <span class="count">{{ $item->comments->count() }}</span>
                </div>
            </div>

            <a href="{{ route('item.purchase', $item->id) }}" class="btn-purchase">購入手続きへ</a>
        </section>

        {{-- 商品説明 --}}
        <section class="item-section">
            <h2 class="section-title">商品説明</h2>
            <p class="description-text">{{ $item->description }}</p>
        </section>

        {{-- 商品の情報 --}}
        <section class="item-section">
            <h2 class="section-title">商品の情報</h2>
            <div class="info-table">
                <div class="info-row" style="margin-bottom: 20px;">
                    <span class="info-label" style="font-weight: bold; margin-right: 20px;">カテゴリー</span>
                    <div class="tags" style="display: inline-block;">
                        @foreach($item->categories as $category)
                        <span class="category-tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label" style="font-weight: bold; margin-right: 20px;">商品の状態</span>
                    <span class="info-value">{{ $item->condition->name }}</span>
                </div>
            </div>
        </section>

        {{-- コメント --}}
        <section class="item-section">
            <h2 class="section-title color-gray">コメント ({{ $item->comments->count() }})</h2>
            <div class="comment-list">
                @foreach($item->comments as $comment)
                <div class="comment-item">
                    {{-- ユーザーアイコン表示の分岐 --}}
                    <div class="comment-user-image">
                        @if($comment->user->image_url)
                        {{-- プロフィール画像がある場合 --}}
                        <img src="{{ asset('storage/' . $comment->user->image_url) }}" alt="ユーザーアイコン" class="user-icon">
                        @else
                        {{-- 画像がない場合は 👤 を表示 --}}
                        <div class="default-user-icon">👤</div>
                        @endif
                    </div>

                    <div class="comment-content">
                        <span class="comment-user-name">{{ $comment->user->name }}</span>
                        <div class="comment-bubble">
                            {{ $comment->comment }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="comment-post">
                <h3 class="post-title" style="font-size: 28px; font-weight: 700; margin-top: 40px;">商品へのコメント</h3>
                @auth
                <form action="{{ route('comment.store', $item->id) }}" method="POST">
                    @csrf
                    <textarea name="comment" class="comment-textarea">{{ old('comment') }}</textarea>
                    @error('comment') <p class="error" style="color: red; margin-top: 5px;">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-comment">コメントを送信する</button>
                </form>
                @endauth
            </div>
        </section>
    </div>
</main>
@endsection