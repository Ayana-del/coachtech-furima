@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
<main class="item-detail">
    <div class="item-detail__left">
        <div class="item-detail__image">
            <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
        </div>
    </div>

    <div class="item-detail__right">
        <section class="item-header">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="brand-name">{{ $item->brand_name }}</p>
            <p class="item-price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

            <div class="stats-row">
                <div class="stat-group">
                    <form action="{{ route('items.like', $item->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="icon-btn">
                            <img src="{{ asset(auth()->check() && $isLiked ? 'img/ハートロゴ_ピンク.png' : 'img/ハートロゴ_デフォルト.png') }}" alt="いいね">
                        </button>
                    </form>
                    <span class="count">{{ $item->likes->count() }}</span>
                </div>
                <div class="stat-group">
                    <div class="icon-btn">
                        <img src="{{ asset('img/ふきだしロゴ.png') }}" alt="コメント">
                    </div>
                    <span class="count">{{ $item->comments->count() }}</span>
                </div>
            </div>

            <a href="{{ route('purchases.show', ['item_id' => $item->id]) }}" class="btn-purchase">購入手続きへ</a>
        </section>

        <section class="item-section">
            <h2 class="section-title">商品説明</h2>
            <p class="description-text">{{ $item->description }}</p>
        </section>

        <section class="item-section">
            <h2 class="section-title">商品の情報</h2>
            <div class="info-table">
                <div class="info-row">
                    <span class="info-label">カテゴリー</span>
                    <div class="tags">
                        @foreach($item->categories as $category)
                        <span class="category-tag">{{ $category->content }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">商品の状態</span>
                    <span class="info-value">{{ $item->condition->name }}</span>
                </div>
            </div>
        </section>

        <section class="item-section">
            <h2 class="section-title">コメント ({{ $item->comments->count() }})</h2>
            <div class="comment-list">
                @foreach($item->comments as $comment)
                <div class="comment-item">
                    <div class="comment-user-image">
                        @if($comment->user->image_url)
                        <img src="{{ asset('storage/' . $comment->user->image_url) }}" alt="ユーザーアイコン" class="user-icon">
                        @else
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
                <h3 class="post-title">商品へのコメント</h3>
                @auth
                <form action="{{ route('comment.store', $item->id) }}" method="POST">
                    @csrf
                    <textarea name="comment" class="comment-textarea">{{ old('comment') }}</textarea>
                    @error('comment') <p class="error">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-comment">コメントを送信する</button>
                </form>
                @endauth
            </div>
        </section>
    </div>
</main>
@endsection