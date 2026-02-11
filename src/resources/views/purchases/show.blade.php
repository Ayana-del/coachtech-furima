@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases/show.css') }}">
@endsection

@section('content')
@if (session('message'))
<div class="alert-success">
    {{ session('message') }}
</div>
@endif
<div class="purchase">
    <form action="{{ route('purchases.store', ['item_id' => $item->id]) }}" method="POST" id="purchase-form">
        @csrf
        <div class="purchase__container">
            <div class="purchase__main">
                <div class="purchase__item">
                    <div class="purchase__item-img">
                        <img src="{{ asset($item->image_url) }}" alt="{{ $item->name }}">
                    </div>
                    <div class="purchase__item-detail">
                        <h2 class="purchase__item-name">{{ $item->name }}</h2>
                        <p class="purchase__item-price">¥{{ number_format($item->price) }}</p>
                    </div>
                </div>

                <div class="purchase__group">
                    <div class="purchase__group-header">
                        <h3>支払い方法</h3>
                    </div>
                    <div class="purchase__select">
                        <select name="payment_method" id="payment_select">
                            <option value="" disabled selected>選択してください</option>
                            <option value="konbini">コンビニ払い</option>
                            <option value="card">カード支払い</option>
                        </select>
                    </div>
                </div>

                <div class="purchase__group">
                    <div class="purchase__group-header">
                        <h3>配送先</h3>
                        <a href="{{ route('addresses.edit', ['item_id' => $item->id]) }}" class="purchase__edit-link">変更する</a>
                    </div>
                    <div class="purchase__address-info">
                        @if($address)
                        <p>〒 {{ $address->postcode }}</p>
                        <p>{{ $address->address }} {{ $address->building }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="purchase__side">
                <div class="purchase__summary">
                    <div class="purchase__summary-row">
                        <span class="summary-label">商品代金</span>
                        <span class="summary-value">¥{{ number_format($item->price) }}</span>
                    </div>
                    <div class="purchase__summary-row">
                        <span class="summary-label">支払い方法</span>
                        <span id="display-payment" class="summary-value"></span>
                    </div>
                </div>

                <button type="submit" class="purchase__btn">購入する</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_select');
        const displayPayment = document.getElementById('display-payment');

        paymentSelect.addEventListener('change', function() {
            const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
            displayPayment.textContent = selectedText;
        });
    });
</script>
@endsection