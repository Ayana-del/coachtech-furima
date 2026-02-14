@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
@if (session('message'))
<div class="alert-success">
    {{ session('message') }}
</div>
@endif

<div class="item-page">
    <div class="tab-container">
        <a href="{{ route('item.index', ['keyword' => $keyword]) }}"
            class="tab-item {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('item.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}"
            class="tab-item {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="item-grid">
        @forelse($items as $item)
        <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-item">
            <div class="item-image">
                <img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : asset('storage/' . $item->image_url) }}" alt="{{ $item->name }}">
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