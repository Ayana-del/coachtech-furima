@extends('layouts.common')

@section('css')
<link rel="stylesheet" href="{{ asset('css/addresses/edit.css') }}">
@endsection

@section('content')
<div class="address-edit-container">
    <h2 class="address-edit-title">住所の変更</h2>

    <form action="{{ route('addresses.update', ['item_id' => $item->id]) }}" method="POST" class="address-edit-form">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="postcode">郵便番号</label>
            <input type="text" name="postcode" id="postcode" value="{{ old('postcode', $profile->postcode ?? '') }}">
            @error('postcode')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building', $profile->building ?? '') }}">
            @error('building')
            <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="address-checkbox-group">
            <label class="checkbox-label">
                <input type="hidden" name="update_profile" value="0">
                <input type="checkbox" name="update_profile" id="update_profile" value="1" {{ old('update_profile', '1') == '1' ? 'checked' : '' }}>
                <span class="checkbox-text">この住所をプロフィールに登録する</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-button">更新する</button>
        </div>
    </form>
</div>
@endsection