@extends('layouts.common')

@section('search')
<form action="{{ route('item.index') }}" method="get" class="header-search-form">
    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/item/index.css') }}">
@endsection

@section('content')
<div class="item-page">
    <div class="tab-container">
        <a href="{{ route('item.index', ['keyword' => $keyword]) }}"
            class="tab-item {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>

        <a href="{{ route('item.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}"
            class="tab-item {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="item-grid">
        @forelse($items as $item)
        {{-- 外側のdivを消すか、別の名前にして aタグをメインにします --}}
        <a href="#" class="item-item">
            <div class="item-image">
                <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                @if($item->is_sold)
                <div class="sold-label">Sold</div>
                @endif
            </div>
            <div class="item-name">
                {{ $item->name }}
            </div>
        </a>
        @empty
        <p class="no-items">表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection