@extends('layouts.common')

@section('content')
<main class="container">
    <div class="item-detail">
        {{-- 左カラム：商品画像 --}}
        <div class="item-detail__image">
            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
        </div>

        {{-- 右カラム：商品情報・アクション --}}
        <div class="item-detail__content">
            {{-- 商品基本情報 --}}
            <section class="item-header">
                <h1 class="item-header__title">{{ $item->name }}</h1>
                <p class="item-header__brand">{{ $item->brand }}</p>
                <p class="item-header__price">
                    <span class="currency">¥</span>{{ number_format($item->price) }} <span class="tax-info">(税込)</span>
                </p>
            </section>

            {{-- いいね・コメント数 --}}
            <section class="item-stats">
                <div class="stat-group">
                    {{-- いいねボタン --}}
                    <i class="fa-star {{ $isLiked ? 'fas text-yellow' : 'far' }}"></i>
                    <span>{{ $item->likes->count() }}</span>
                </div>
                <div class="stat-group">
                    <i class="far fa-comment"></i>
                    <span>{{ $item->comments->count() }}</span>
                </div>
            </section>

            {{-- 購入アクション --}}
            <div class="item-action">
                <a href="#" class="btn btn-primary">購入手続きへ</a>
            </div>

            {{-- 商品説明 --}}
            <section class="item-description">
                <h2 class="section-title">商品説明</h2>
                <p>{{ $item->description }}</p>
            </section>

            {{-- 商品情報詳細 --}}
            <section class="item-info">
                <h2 class="section-title">商品の情報</h2>
                <div class="info-row">
                    <span class="info-label">カテゴリー</span>
                    <div class="category-tags">
                        @foreach($item->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="info-row">
                    <span class="info-label">商品の状態</span>
                    <span class="info-value">{{ $item->condition->name }}</span>
                </div>
            </section>

            {{-- コメント一覧  --}}
            <section class="item-comments">
                <h2 class="section-title">コメント ({{ $item->comments->count() }})</h2>
                @foreach($item->comments as $comment)
                <div class="comment-card">
                    <div class="user-info">
                        <img src="{{ $comment->user->image_url }}" alt="">
                        <span class="username">{{ $comment->user->name }}</span>
                    </div>
                    <p class="comment-text">{{ $comment->comment }}</p>
                </div>
                @endforeach
            </section>
        </div>
    </div>
</main>
@endsection