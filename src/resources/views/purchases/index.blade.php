@extends('layouts.common')

@section('title', '商品の購入')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/index.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__container">
        {{-- 左側：入力エリア --}}
        <div class="purchase__main">
            {{-- 商品概要 --}}
            <div class="purchase__item">
                <div class="purchase__item-img">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase__item-detail">
                    <h2 class="purchase__item-name">{{ $item->name }}</h2>
                    <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 支払い方法選択 (ID11) --}}
            <div class="purchase__group">
                <div class="purchase__group-header">
                    <h3>支払い方法</h3>
                    {{-- 現状の要件に合わせてリンク先は適宜調整してください --}}
                </div>
                <div class="purchase__select">
                    <select name="payment_method" id="payment_select" form="purchase-form">
                        <option value="" disabled selected>選択してください</option>
                        <option value="konbini">コンビニ払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                </div>
            </div>

            {{-- 配送先 (ID12) --}}
            <div class="purchase__group">
                <div class="purchase__group-header">
                    <h3>配送先</h3>
                    <a href="{{ route('addresses.edit', ['item_id' => $item->id]) }}" class="purchase__edit-link">変更する</a>
                </div>
                <div class="purchase__address-info">
                    <p>〒 {{ Auth::user()->profile->postal_code ?? '000-0000' }}</p>
                    <p>{{ Auth::user()->profile->address ?? '住所が登録されていません' }}</p>
                    <p>{{ Auth::user()->profile->building ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- 右側：確認エリア --}}
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

<script>
    // 支払い方法の即時反映 (ID11)
    const select = document.getElementById('payment_select');
    const display = document.getElementById('display-payment');
    select.addEventListener('change', () => {
        display.textContent = select.options[select.selectedIndex].text;
    });
</script>
@endsection