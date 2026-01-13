@extends('layouts.common')

@section('search')
<form action="{{ route('product.index') }}" method="GET" class="search-form">
    @if(isset($tab))
    <input type="hidden" name="tab" value="{{ $tab }}">
    @endif
    <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/products/index.css') }}">
@endsection

@section('content')
<div class="product-page">
    <div class="tab-container">
        <a href="{{ route('product.index', ['keyword' => $keyword]) }}"
            class="tab-item {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>

        <a href="{{ route('product.index', ['tab' => 'mylist', 'keyword' => $keyword]) }}"
            class="tab-item {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
        <div class="product-item">
            <a href="{{ route('product.show', ['item_id' => $product->id]) }}">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}">

                    @if($product->is_sold)
                    <div class="sold-label">Sold</div>
                    @endif
                </div>
                <div class="product-name">
                    {{ $product->name }}
                </div>
            </a>
        </div>
        @empty
        <p class="no-items">表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection