@extends('layouts.common')

@section('content')
<div class="address-edit-container">
    <h2 class="address-edit-title">住所の変更</h2>

    <form action="{{ route('addresses.update', ['item_id' => $item->id]) }}" method="POST" class="address-edit-form">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            @error('postal_code')
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

        <div class="form-actions">
            <button type="submit" class="submit-button">更新する</button>
        </div>
    </form>
</div>
@endsection