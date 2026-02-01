@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/index.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__container">
        <div class="purchase__main">
            {{-- 商品情報 --}}
            <div class="purchase__item">
                <div class="purchase__item-img">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase__item-detail">
                    <h2 class="purchase__item-name">{{ $item->name }}</h2>
                    <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="purchase__group">
                <div class="purchase__group-header">
                    <h3>支払い方法</h3>
                </div>
                <div class="purchase__select">
                    <select name="payment_method" id="payment_select" form="purchase-form">
                        <option value="" disabled selected>選択してください</option>
                        <option value="コンビニ払い">コンビニ払い</option>
                        <option value="カード支払い">カード支払い</option>
                    </select>
                    @error('payment_method')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 配送先 --}}
            <div class="purchase__group">
                <div class="purchase__group-header">
                    <h3>配送先</h3>
                    <a href="{{ route('addresses.edit', ['item_id' => $item->id]) }}" class="purchase__edit-link">変更する</a>
                </div>
                <div class="purchase__address-info">
                    @error('address_check')
                    <p class="error-message">{{ $message }}</p>
                    @enderror

                    @if($profile)
                    <p>〒 {{ $profile->postal_code }}</p>
                    <p>{{ $profile->address }}</p>
                    <p>{{ $profile->building }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- 確認エリア --}}
        <div class="purchase__side">
            <div class="purchase__summary">
                <div class="purchase__summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }}</span>
                </div>
                <div class="purchase__summary-row">
                    <span>支払い方法</span>
                    <span id="display-payment">未選択</span>
                </div>
            </div>

            <form action="{{ route('purchases.store', $item->id) }}" method="POST" id="purchase-form">
                @csrf
                <button type="submit" class="purchase__btn" {{ $item->is_sold ? 'disabled' : '' }}>
                    {{ $item->is_sold ? '売り切れ' : '購入する' }}
                </button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/purchase.js') }}"></script>
@endsection