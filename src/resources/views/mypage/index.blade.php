@extends('layouts.common')

@section('search')
<form action="{{ route('item.index') }}" method="get" class="header-search-form">
    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/item/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
<div class="item-page">

    {{-- ユーザー情報セクション --}}
    <div class="mypage-user-section">
        <div class="user-info-container">
            <div class="user-image-wrapper">
                @if($user->profile && $user->profile->image_url)
                <img src="{{ Str::startsWith($user->profile->image_url, ['http://', 'https://']) ? $user->profile->image_url : asset('storage/' . $user->profile->image_url) }}" class="user-image">
                @else
                <span class="default-user-icon">👤</span>
                @endif
            </div>

            <h2 class="user-name">{{ $user->profile->name ?? $user->name }}</h2>

            <div class="edit-button-wrapper">
                <a href="{{ route('profile.edit') }}" class="profile-edit-btn">プロフィールを編集</a>
            </div>
        </div>
    </div>

    {{-- タブメニュー --}}
    <div class="tab-container">
        <p class="tab-item active" id="tab-sell">出品した商品</p>
        <p class="tab-item" id="tab-buy">購入した商品</p>
    </div>

    {{-- 出品した商品一覧 (初期表示) --}}
    <div id="sell-items" class="item-grid">
        @forelse($sellItems as $item)
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-item">
            <div class="item-image">
                <img src="{{ Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                @if($item->is_sold)
                <div class="sold-label">Sold</div>
                @endif
            </div>
            <div class="item-name">{{ $item->name }}</div>
        </a>
        @empty
        <p class="no-items">出品した商品がありません。</p>
        @endforelse
    </div>

    {{-- 購入した商品一覧 (初期は非表示) --}}
    <div id="buy-items" class="item-grid" style="display: none;">
        @forelse($buyItems as $item)
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-item">
            <div class="item-image">
                <img src="{{ Str::startsWith($item->image_url, ['http://', 'https://']) ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
                @if($item->is_sold)
                <div class="sold-label">Sold</div>
                @endif
            </div>
            <div class="item-name">{{ $item->name }}</div>
        </a>
        @empty
        <p class="no-items">購入した商品がありません。</p>
        @endforelse
    </div>
</div>

{{-- タブ切り替え用スクリプト --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabSell = document.getElementById('tab-sell');
        const tabBuy = document.getElementById('tab-buy');
        const sellItems = document.getElementById('sell-items');
        const buyItems = document.getElementById('buy-items');

        tabSell.addEventListener('click', () => {
            tabSell.classList.add('active');
            tabBuy.classList.remove('active');
            sellItems.style.display = 'grid';
            buyItems.style.display = 'none';
        });

        tabBuy.addEventListener('click', () => {
            tabBuy.classList.add('active');
            tabSell.classList.remove('active');
            buyItems.style.display = 'grid';
            sellItems.style.display = 'none';
        });
    });
</script>
@endsection