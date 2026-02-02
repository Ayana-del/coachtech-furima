@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/index.css') }}">
@endsection

@section('content')
<div class="purchase">
    <div class="purchase__container">
        <div class="purchase__main">
            {{-- 1. 商品情報 --}}
            <div class="purchase__item">
                <div class="purchase__item-img">
                    <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                </div>
                <div class="purchase__item-detail">
                    <h2 class="purchase__item-name">{{ $item->name }}</h2>
                    <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                </div>
            </div>

            {{-- 2. 支払い方法（プルダウン） --}}
            <div class="purchase__group">
                <div class="purchase__group-header">
                    <h3>支払い方法</h3>
                </div>
                <div class="purchase__select">
                    {{-- valueを英字にすることでStripe連携をスムーズにします --}}
                    <select name="payment_method" id="payment_select" form="purchase-form">
                        <option value="" disabled selected>選択してください</option>
                        <option value="konbini">コンビニ払い</option>
                        <option value="card">カード支払い</option>
                    </select>
                    @error('payment_method')
                    <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 3. 配送先 --}}
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

        {{-- 4. 確認エリア（小計画面） --}}
        <div class="purchase__side">
            <div class="purchase__summary">
                <div class="purchase__summary-row">
                    <span class="summary-label">商品代金</span>
                    <span class="summary-value">¥{{ number_format($item->price) }}</span>
                </div>
                <div class="purchase__summary-row">
                    <span class="summary-label">支払い方法</span>
                    {{-- ここのテキストがJSで即時切り替わります --}}
                    <span id="display-payment" class="summary-value">未選択</span>
                </div>
            </div>

            <form action="{{ route('purchases.store', $item->id) }}" method="POST" id="purchase-form">
                @csrf
                {{-- payment_methodを送信するため、selectと連動させるかhiddenで持つ必要があります --}}
                {{-- 今回はselect側に form="purchase-form" を付けているのでこのまま送信可能です --}}
                <button type="submit" class="purchase__btn" {{ $item->is_sold ? 'disabled' : '' }}>
                    {{ $item->is_sold ? '売り切れ' : '購入する' }}
                </button>
            </form>
        </div>
    </div>
</div>

{{-- 支払い方法の即時反映スクリプト --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_select');
        const displayPayment = document.getElementById('display-payment');

        paymentSelect.addEventListener('change', function() {
            // 選択された項目のテキスト（コンビニ払い等）を取得して反映
            const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
            displayPayment.textContent = selectedText;
        });
    });
</script>

<script src="{{ asset('js/purchase.js') }}"></script>
@endsection